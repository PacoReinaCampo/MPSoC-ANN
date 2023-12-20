module testbench;
  // m-bit processor
  parameter m = 8;

  // Clock and Reset declaration
  reg          clk;
  reg          reset;

  // Data Signals
  reg  [ 15:0] instruction;

  reg  [m-1:0] IN0;
  reg  [m-1:0] IN1;
  reg  [m-1:0] IN2;
  reg  [m-1:0] IN3;
  reg  [m-1:0] IN4;
  reg  [m-1:0] IN5;
  reg  [m-1:0] IN6;
  reg  [m-1:0] IN7;
  reg  [m-1:0] IN8;
  reg  [m-1:0] IN9;
  reg  [m-1:0] IN10;
  reg  [m-1:0] IN11;
  reg  [m-1:0] IN12;
  reg  [m-1:0] IN13;
  reg  [m-1:0] IN14;
  reg  [m-1:0] IN15;

  wire [m-1:0] OUT0;
  wire [m-1:0] OUT1;
  wire [m-1:0] OUT2;
  wire [m-1:0] OUT3;
  wire [m-1:0] OUT4;
  wire [m-1:0] OUT5;
  wire [m-1:0] OUT6;
  wire [m-1:0] OUT7;
  wire [m-1:0] OUT8;
  wire [m-1:0] OUT9;
  wire [m-1:0] OUT10;
  wire [m-1:0] OUT11;
  wire [m-1:0] OUT12;
  wire [m-1:0] OUT13;
  wire [m-1:0] OUT14;
  wire [m-1:0] OUT15;

  wire [  7:0] pc;

  // DUT instantiation
  processor dut (
    .clk(clk),

    .reset(reset),

    .instruction(instruction),

    .IN0 (IN0),
    .IN1 (IN1),
    .IN2 (IN2),
    .IN3 (IN3),
    .IN4 (IN4),
    .IN5 (IN5),
    .IN6 (IN6),
    .IN7 (IN7),
    .IN8 (IN8),
    .IN9 (IN9),
    .IN10(IN10),
    .IN11(IN11),
    .IN12(IN12),
    .IN13(IN13),
    .IN14(IN14),
    .IN15(IN15),

    .OUT0 (OUT0),
    .OUT1 (OUT1),
    .OUT2 (OUT2),
    .OUT3 (OUT3),
    .OUT4 (OUT4),
    .OUT5 (OUT5),
    .OUT6 (OUT6),
    .OUT7 (OUT7),
    .OUT8 (OUT8),
    .OUT9 (OUT9),
    .OUT10(OUT10),
    .OUT11(OUT11),
    .OUT12(OUT12),
    .OUT13(OUT13),
    .OUT14(OUT14),
    .OUT15(OUT15),

    .pc(pc)
  );

  // Clock Generation
  always #1 clk = ~clk;

  // Reset Generation
  initial begin
    reset = 1;
    #5;
    reset = 0;
  end

  initial begin
    // Dump waves
    $dumpfile("system.vcd");
    $dumpvars(0, testbench);

    clk                = 0;

    IN0                = 0;
    IN1                = 0;
    IN2                = 0;
    IN3                = 0;
    IN4                = 0;
    IN5                = 0;
    IN6                = 0;
    IN7                = 0;
    IN8                = 0;
    IN9                = 0;
    IN10               = 0;
    IN11               = 0;
    IN12               = 0;
    IN13               = 0;
    IN14               = 0;
    IN15               = 0;

    instruction[15:0] = 16'b0000000000000000;
    #5;

    repeat (50) begin
      // ASSIGN_VALUE
      IN0                = $urandom();
      IN1                = $urandom();
      IN2                = $urandom();
      IN3                = $urandom();
      IN4                = $urandom();
      IN5                = $urandom();
      IN6                = $urandom();
      IN7                = $urandom();
      IN8                = $urandom();
      IN9                = $urandom();
      IN10               = $urandom();
      IN11               = $urandom();
      IN12               = $urandom();
      IN13               = $urandom();
      IN14               = $urandom();
      IN15               = $urandom();

      instruction[15:12] = 4'b0000;
      instruction[11:0]  = $urandom();
      #5;

      // DATA_INPUT
      IN0                = $urandom();
      IN1                = $urandom();
      IN2                = $urandom();
      IN3                = $urandom();
      IN4                = $urandom();
      IN5                = $urandom();
      IN6                = $urandom();
      IN7                = $urandom();
      IN8                = $urandom();
      IN9                = $urandom();
      IN10               = $urandom();
      IN11               = $urandom();
      IN12               = $urandom();
      IN13               = $urandom();
      IN14               = $urandom();
      IN15               = $urandom();

      instruction[15:12] = 4'b0010;
      instruction[11:0]  = $urandom();
      #5;

      // DATA_OUTPUT
      IN0                = $urandom();
      IN1                = $urandom();
      IN2                = $urandom();
      IN3                = $urandom();
      IN4                = $urandom();
      IN5                = $urandom();
      IN6                = $urandom();
      IN7                = $urandom();
      IN8                = $urandom();
      IN9                = $urandom();
      IN10               = $urandom();
      IN11               = $urandom();
      IN12               = $urandom();
      IN13               = $urandom();
      IN14               = $urandom();
      IN15               = $urandom();

      instruction[15:12] = 4'b1010;
      instruction[11:0]  = $urandom();
      #5;

      // OUTPUT_VALUE
      IN0                = $urandom();
      IN1                = $urandom();
      IN2                = $urandom();
      IN3                = $urandom();
      IN4                = $urandom();
      IN5                = $urandom();
      IN6                = $urandom();
      IN7                = $urandom();
      IN8                = $urandom();
      IN9                = $urandom();
      IN10               = $urandom();
      IN11               = $urandom();
      IN12               = $urandom();
      IN13               = $urandom();
      IN14               = $urandom();
      IN15               = $urandom();

      instruction[15:12] = 4'b1000;
      instruction[11:0]  = $urandom();
      #5;

      // ADDITION
      IN0                = $urandom();
      IN1                = $urandom();
      IN2                = $urandom();
      IN3                = $urandom();
      IN4                = $urandom();
      IN5                = $urandom();
      IN6                = $urandom();
      IN7                = $urandom();
      IN8                = $urandom();
      IN9                = $urandom();
      IN10               = $urandom();
      IN11               = $urandom();
      IN12               = $urandom();
      IN13               = $urandom();
      IN14               = $urandom();
      IN15               = $urandom();

      instruction[15:12] = 4'b0100;
      instruction[11:0]  = $urandom();
      #5;

      // SUBTRACTION
      IN0                = $urandom();
      IN1                = $urandom();
      IN2                = $urandom();
      IN3                = $urandom();
      IN4                = $urandom();
      IN5                = $urandom();
      IN6                = $urandom();
      IN7                = $urandom();
      IN8                = $urandom();
      IN9                = $urandom();
      IN10               = $urandom();
      IN11               = $urandom();
      IN12               = $urandom();
      IN13               = $urandom();
      IN14               = $urandom();
      IN15               = $urandom();

      instruction[15:12] = 4'b0101;
      instruction[11:0]  = $urandom();
      #5;

      // JUMP
      IN0                = $urandom();
      IN1                = $urandom();
      IN2                = $urandom();
      IN3                = $urandom();
      IN4                = $urandom();
      IN5                = $urandom();
      IN6                = $urandom();
      IN7                = $urandom();
      IN8                = $urandom();
      IN9                = $urandom();
      IN10               = $urandom();
      IN11               = $urandom();
      IN12               = $urandom();
      IN13               = $urandom();
      IN14               = $urandom();
      IN15               = $urandom();

      instruction[15:12] = 4'b1110;
      instruction[11:0]  = $urandom();
      #5;

      // JUMP_POSITIVE
      IN0                = $urandom();
      IN1                = $urandom();
      IN2                = $urandom();
      IN3                = $urandom();
      IN4                = $urandom();
      IN5                = $urandom();
      IN6                = $urandom();
      IN7                = $urandom();
      IN8                = $urandom();
      IN9                = $urandom();
      IN10               = $urandom();
      IN11               = $urandom();
      IN12               = $urandom();
      IN13               = $urandom();
      IN14               = $urandom();
      IN15               = $urandom();

      instruction[15:12] = 4'b1100;
      instruction[11:0]  = $urandom();
      #5;

      // JUMP_NEGATIVE
      IN0                = $urandom();
      IN1                = $urandom();
      IN2                = $urandom();
      IN3                = $urandom();
      IN4                = $urandom();
      IN5                = $urandom();
      IN6                = $urandom();
      IN7                = $urandom();
      IN8                = $urandom();
      IN9                = $urandom();
      IN10               = $urandom();
      IN11               = $urandom();
      IN12               = $urandom();
      IN13               = $urandom();
      IN14               = $urandom();
      IN15               = $urandom();

      instruction[15:12] = 4'b1101;
      instruction[11:0]  = $urandom();
      #5;
    end

    $display("End");
    $finish();
  end
endmodule
