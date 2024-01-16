program tb_noc_router_test (
  input clk,
  input rst,

  testbench_if tb_if
);

  parameter vchannels = 1;

  expect_queue #(.vchannels(vchannels)) eq = new();

  initial begin
    eq = new();

    @(negedge rst);
    fork
      packet_generate(0);
      packet_generate(1);
      packet_generate(2);
      packet_generate(3);
      packet_generate(4);
      packet_monitor(0);
      packet_monitor(1);
      packet_monitor(2);
      packet_monitor(3);
      packet_monitor(4);
    join
  end

  task automatic packet_generate(int id);
    packet                             p;

    flit_header_t #(`DATA_WIDTH)       header;
    flit_t #(`DATA_WIDTH, `TYPE_WIDTH) flit;
    sent_flit                          sflit;

    p           = new();
    p.self      = id;
    p.vchannels = vchannels;

    forever begin
      void'(p.randomize());
      header                     = new();
      flit                       = new();
      header.dest                = p.dest;
      header.prio                = p.prio;
      header.class_specific[3:0] = id;

      flit.ftype                 = HEADER;
      flit.content               = {header.dest, header.prio, header.packet_class, header.class_specific};
      tb_if.send(id, p.dest, p.vc, flit, p.burst_delay);
      sflit.flit      = flit;
      sflit.timestamp = $time;
      eq.put(id, p.dest, p.vc, sflit);  //--> write into queue

      for (int i = 0; i < $size(p.payload) - 1; i = i + 1) begin
        header       = new();
        flit         = new();
        flit.ftype   = PAYLOAD;
        flit.content = p.payload[i];
        tb_if.send(id, p.dest, p.vc, flit, p.burst_delay);
        sflit.flit      = flit;
        sflit.timestamp = $time;
        eq.put(id, p.dest, p.vc, sflit);  //--> write into queue
      end

      header       = new();
      flit         = new();
      flit.ftype   = LAST;
      flit.content = p.payload[$size(p.payload)-1];
      tb_if.send(id, p.dest, p.vc, flit, p.burst_delay);
      sflit.flit      = flit;
      sflit.timestamp = $time;
      eq.put(id, p.dest, p.vc, sflit);  //--> write into queue
    end
  endtask

  task automatic packet_monitor(int id);
    flit_t #(`DATA_WIDTH, `TYPE_WIDTH) flit = new();
    int                                vc;
    int                                source;
    randomdelay                        d = new();

    forever begin
      void'(d.randomize());
      tb_if.receive(id, d.delay, vc, flit);
      eq.check(id, flit, vc, source);
    end

  endtask  // packet_monitor

endprogram  // tb_noc_router_test
