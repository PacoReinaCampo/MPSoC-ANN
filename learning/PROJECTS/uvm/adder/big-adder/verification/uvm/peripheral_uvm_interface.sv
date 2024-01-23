interface peripheral_uvm_interface (
  input logic clk,
  input logic rst,
  input logic in_valid
);

  // Declaration of Signals
  logic [7:0] in1;
  logic [7:0] in2;
  logic       out_valid;
  logic [15:0] data_out;

  // Clocking block and modport declaration for driver
  clocking dr_cb @(posedge clk);
    output in1;
    output in2;
    input out_valid;
    input data_out;
  endclocking

  modport DRV(clocking dr_cb, input clk, rst, in_valid);

  // Clocking block and modport declaration for monitor
  clocking rc_cb @(negedge clk);
    input in1;
    input in2;
    input out_valid;
    input data_out;
  endclocking

  modport RCV(clocking rc_cb, input clk, rst, in_valid);
endinterface
