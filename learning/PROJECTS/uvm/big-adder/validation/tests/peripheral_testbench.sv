module peripheral_testbench;
  // Clock and Reset declaration
  reg        clk;
  reg        rst;

  // Data Signals
  reg        in_valid;
  wire       out_valid;

  // Data Signals
  reg  [7:0] in1;
  reg  [7:0] in2;

  wire [8:0] data_out;

  // DUT instantiation
  peripheral_adder  #(
    .DATA_SIZE(8)
  ) dut (
    .clk(clk),
    .rst(rst),

    .in_valid(in_valid),
    .out_valid(out_valid),

    .in1(in1),
    .in2(in2),

    .data_out(data_out)
  );

  // Clock Generation
  always #1 clk = ~clk;

  initial begin
    clk = 0;
  end

  // Reset Generation
  initial begin
    rst = 0;
    #3;

    rst = 1;
    #2;

    rst = 0;
    #4;
  end

  // Start Generation
  initial begin
    in_valid = 0;
    #7;

    repeat (10) begin
      in_valid = 1;
      #2;

      in_valid = 0;
      #10;
    end
  end

  initial begin
    // Dump waves
    $dumpfile("system.vcd");
    $dumpvars(0, peripheral_testbench);

    in1 = 0;
    in2 = 0;
    #7;

    repeat (10) begin
      in1 = $urandom();
      in2 = $urandom();
      #12;
    end

    $display("End");
    $finish();
  end
endmodule
