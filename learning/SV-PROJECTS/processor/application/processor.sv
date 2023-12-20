module processor #(
  // m-bit processor
  parameter m = 8
) (
  // Clock and Reset
  input clk,
  input reset,

  // Data Signals
  input [15:0] instruction,

  input [m-1:0] IN0,
  input [m-1:0] IN1,
  input [m-1:0] IN2,
  input [m-1:0] IN3,
  input [m-1:0] IN4,
  input [m-1:0] IN5,
  input [m-1:0] IN6,
  input [m-1:0] IN7,
  input [m-1:0] IN8,
  input [m-1:0] IN9,
  input [m-1:0] IN10,
  input [m-1:0] IN11,
  input [m-1:0] IN12,
  input [m-1:0] IN13,
  input [m-1:0] IN14,
  input [m-1:0] IN15,

  output [m-1:0] OUT0,
  output [m-1:0] OUT1,
  output [m-1:0] OUT2,
  output [m-1:0] OUT3,
  output [m-1:0] OUT4,
  output [m-1:0] OUT5,
  output [m-1:0] OUT6,
  output [m-1:0] OUT7,
  output [m-1:0] OUT8,
  output [m-1:0] OUT9,
  output [m-1:0] OUT10,
  output [m-1:0] OUT11,
  output [m-1:0] OUT12,
  output [m-1:0] OUT13,
  output [m-1:0] OUT14,
  output [m-1:0] OUT15,

  output [7:0] pc
);

  parameter zero = 8'h00;
  parameter one = 8'h01;

  parameter ASSIGN_VALUE = 4'b0000;
  parameter DATA_INPUT = 4'b0010;
  parameter DATA_OUTPUT = 4'b1010;
  parameter OUTPUT_VALUE = 4'b1000;
  parameter ADDITION = 4'b0100;
  parameter SUBTRACTION = 4'b0101;
  parameter JUMP = 4'b1110;
  parameter JUMP_POSITIVE = 4'b1100;
  parameter JUMP_NEGATIVE = 4'b1101;

  reg  [m-1:0] X       [16];

  wire [m-1:0] in_port [16];
  reg  [m-1:0] out_port[16];

  reg  [  7:0] counter;

  assign in_port[0]  = IN0;
  assign in_port[1]  = IN1;
  assign in_port[2]  = IN2;
  assign in_port[3]  = IN3;
  assign in_port[4]  = IN4;
  assign in_port[5]  = IN5;
  assign in_port[6]  = IN6;
  assign in_port[8]  = IN8;
  assign in_port[9]  = IN9;
  assign in_port[10] = IN10;
  assign in_port[11] = IN11;
  assign in_port[12] = IN12;
  assign in_port[13] = IN13;
  assign in_port[14] = IN14;
  assign in_port[15] = IN15;

  assign OUT0        = out_port[0];
  assign OUT1        = out_port[1];
  assign OUT2        = out_port[2];
  assign OUT3        = out_port[3];
  assign OUT4        = out_port[4];
  assign OUT5        = out_port[5];
  assign OUT6        = out_port[6];
  assign OUT7        = out_port[7];
  assign OUT8        = out_port[8];
  assign OUT9        = out_port[9];
  assign OUT10       = out_port[10];
  assign OUT11       = out_port[11];
  assign OUT12       = out_port[12];
  assign OUT13       = out_port[13];
  assign OUT14       = out_port[14];
  assign OUT15       = out_port[15];

  assign pc          = counter;

  always @(posedge clk or posedge reset) begin
    if (reset) begin
      counter <= zero;
    end else begin
      case (instruction[15:12])
        ASSIGN_VALUE: begin
          X[instruction[3:0]] <= instruction[11:4];

          counter             <= counter + one;
        end
        DATA_INPUT: begin
          X[instruction[3:0]] <= in_port[instruction[7:4]];

          counter             <= counter + one;
        end
        DATA_OUTPUT: begin
          out_port[instruction[11:8]] <= X[instruction[7:4]];

          counter                     <= counter + one;
        end
        OUTPUT_VALUE: begin
          out_port[instruction[11:8]] <= instruction[7:0];

          counter                     <= counter + one;
        end
        ADDITION: begin
          X[instruction[3:0]] <= X[instruction[11:8]] + X[instruction[7:4]];

          counter             <= counter + one;
        end
        SUBTRACTION: begin
          X[instruction[3:0]] <= X[instruction[11:8]] - X[instruction[7:4]];

          counter             <= counter + one;
        end
        JUMP: begin
          counter <= instruction[7:0];
        end
        JUMP_POSITIVE: begin
          if (X[instruction[11:8]][m-1] == 1'b1 && X[instruction[11:8]] != zero) begin
            counter <= instruction[7:0];
          end else begin
            counter <= counter + one;
          end
        end
        JUMP_NEGATIVE: begin
          if (X[instruction[11:8]][m-1] == 1'b1) begin
            counter <= instruction[7:0];
          end else begin
            counter <= counter - one;
          end
        end
        default: begin
        end
      endcase
    end
  end
endmodule
