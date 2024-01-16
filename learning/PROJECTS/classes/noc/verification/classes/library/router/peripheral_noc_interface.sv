interface testbench_if (
  input                clk,
        lisnoc_link_if north_in,
        lisnoc_link_if north_out,
        lisnoc_link_if east_in,
        lisnoc_link_if east_out,
        lisnoc_link_if south_in,
        lisnoc_link_if south_out,
        lisnoc_link_if west_in,
        lisnoc_link_if west_out,
        lisnoc_link_if local_in,
        lisnoc_link_if local_out
);

  parameter vchannels = 1;

  reg [  vchannels-1:0] in_valid        [5];
  reg [  vchannels-1:0] in_ready        [5];
  reg [`DATA_WIDTH-1:0] in_flit_content [5];
  reg [`TYPE_WIDTH-1:0] in_flit_type    [5];

  reg [  vchannels-1:0] out_valid       [5];
  reg [  vchannels-1:0] out_ready       [5];
  reg [`DATA_WIDTH-1:0] out_flit_content[5];
  reg [`DATA_WIDTH-1:0] out_flit_type   [5];

  assign north_in.flit   = {in_flit_type[0], in_flit_content[0]};
  assign east_in.flit    = {in_flit_type[1], in_flit_content[1]};
  assign south_in.flit   = {in_flit_type[2], in_flit_content[2]};
  assign west_in.flit    = {in_flit_type[3], in_flit_content[3]};
  assign local_in.flit   = {in_flit_type[4], in_flit_content[4]};

  assign north_in.valid  = in_valid[0];
  assign east_in.valid   = in_valid[1];
  assign south_in.valid  = in_valid[2];
  assign west_in.valid   = in_valid[3];
  assign local_in.valid  = in_valid[4];

  assign north_out.ready = out_ready[0];
  assign east_out.ready  = out_ready[1];
  assign south_out.ready = out_ready[2];
  assign west_out.ready  = out_ready[3];
  assign local_out.ready = out_ready[4];

  always @(posedge clk) begin
    out_valid[0]                            <= north_out.valid;
    out_valid[1]                            <= east_out.valid;
    out_valid[2]                            <= south_out.valid;
    out_valid[3]                            <= west_out.valid;
    out_valid[4]                            <= local_out.valid;
    {out_flit_type[0], out_flit_content[0]} <= north_out.flit;
    {out_flit_type[1], out_flit_content[1]} <= east_out.flit;
    {out_flit_type[2], out_flit_content[2]} <= south_out.flit;
    {out_flit_type[3], out_flit_content[3]} <= west_out.flit;
    {out_flit_type[4], out_flit_content[4]} <= local_out.flit;
    in_ready[0]                             <= north_in.ready;
    in_ready[1]                             <= east_in.ready;
    in_ready[2]                             <= south_in.ready;
    in_ready[3]                             <= west_in.ready;
    in_ready[4]                             <= local_in.ready;
  end

  initial begin
    for (int i = 0; i < 5; i = i + 1) begin
      for (int j = 0; j < vchannels; j = j + 1) begin
        out_ready[i][j] = 1'b0;
        in_valid[i][j]  = 1'b0;
      end
    end
  end

  task automatic send(int source, int target, int vc, flit_t#(`DATA_WIDTH, `TYPE_WIDTH) flit, int delay);
    repeat (delay) @(posedge clk);
    in_valid[source][vc]    = 1'b1;
    in_flit_content[source] = flit.content;
    in_flit_type[source]    = flit.ftype;
    #1;
    in_valid[source][vc] = 1'b1;
    @(posedge clk iff in_ready[source][vc]);
    $display("%t sent from %3d to %3d on vc %1d: %x%x", $time, source, target, vc, flit.ftype, flit.content);
    in_valid[source][vc] = 1'b0;
  endtask  // send

  task automatic receive(int target, int delay, ref int vc, ref flit_t#(`DATA_WIDTH, `TYPE_WIDTH) flit);
    repeat (delay) @(posedge clk);
    out_ready[target] = {vchannels{1'b1}};

    @(posedge clk iff |out_valid[target]);
    {flit.ftype, flit.content} = {out_flit_type[target], out_flit_content[target]};

    for (int i = 0; i < vchannels; i = i + 1) begin
      if (out_valid[target][i]) begin
        vc = i;
        $display("%t received for %3d on vc %1d: %x%x", $time, target, vc, flit.ftype, flit.content);
        break;
      end
    end
    out_ready[target] = {vchannels{1'b0}};
  endtask

endinterface  // testbench_if
