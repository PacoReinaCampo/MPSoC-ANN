class expect_queue #(
  int vchannels = 1
);

  mailbox #(sent_flit) queue        [5][vchannels] [5];

  semaphore            sem_recv;
  integer              recv_success;
  real                 avg_latency;

  integer              cur_src      [5][vchannels];

  function new();
    for (int i = 0; i < 5; i++) begin
      for (int j = 0; j < vchannels; j++) begin
        cur_src[i][j] = -1;
        for (int k = 0; k < 5; k++) begin
          queue[i][j][k] = new();
        end
      end
    end
    recv_success = 0;
    avg_latency  = 0.0;
    sem_recv     = new(1);
  endfunction  // new

  // Its automatic, so we can re-enter the task while the previous task is still running!!!
  task automatic put(int source, int target, int vc, sent_flit flit);
    queue[target][vc][source].put(flit);
  endtask  // put

  task automatic receive_stat(sent_flit f);
    integer c;
    integer hand1;

    sem_recv.get(1);
    avg_latency = (avg_latency * recv_success + real'($time - f.timestamp)) / (recv_success + 1);
    c           = ++recv_success;
    sem_recv.put(1);

    if (c % 10000 == 0) begin
      $display("%t flits: %0d %f", $time, c, avg_latency);
    end
  endtask  // receive_stat

  // This task checks if the transmission was successfull or not
  task automatic check(int target, flit_t#(`DATA_WIDTH, `TYPE_WIDTH) flit, int vc, ref int source);
    sent_flit f;
    if (cur_src[target][vc] == -1) begin
      for (int s = 0; s < 5; s++) begin
        if (queue[target][vc][s].try_peek(f)) begin
          if (f.flit.content == flit.content) begin
            queue[target][vc][s].get(f);
            source              = s;
            cur_src[target][vc] = s;
            receive_stat(f);
            return;
          end
        end
      end  // for (int s=0;s<5;s++)
    end else begin  // if (cur_src == -1 )
      if (queue[target][vc][cur_src[target][vc]].try_peek(f)) begin
        if (f.flit.content == flit.content) begin
          queue[target][vc][cur_src[target][vc]].get(f);
          source = cur_src[target][vc];
          if (flit.ftype == LAST) begin
            cur_src[target][vc] = -1;
          end
          receive_stat(f);
          return;
        end
      end
    end

    $display("%t Mismatch for %x on %0d[%1d]", $time, flit.content, target, vc);

    if (cur_src[target][vc] == -1) begin
      $display("%t not part of a packet", $time);
    end else begin
      $display("%t expected on %0d", $time, cur_src[target][vc]);
    end

    for (int s = 0; s < 5; s++) begin
      if (queue[target][vc][s].try_peek(f)) begin
        $display("%t   [%0d] (%x%x,%0t)", $time, s, f.flit.ftype, f.flit.content, f.timestamp);
      end
    end

    $fatal(1, "Mismatch");  // This stops the simulation immediately

  endtask  // check
endclass  // expect_queue
