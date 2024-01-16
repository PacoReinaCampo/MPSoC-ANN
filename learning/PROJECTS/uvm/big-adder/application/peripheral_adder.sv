module peripheral_adder (
  input clk,
  input rst,

  input [7:0] in1,
  input [7:0] in2,

  output reg [8:0] out
);

  always @(posedge clk or posedge rst) begin
    if (rst) begin
      out <= 0;
    end else begin
      out <= in1 + in2;
    end
  end
endmodule
