interface peripheral_uvm_interface (
  input logic clk,
  input logic rst
);

  // Declaration of Signals
  logic [7:0] in1;
  logic [7:0] in2;
  logic [8:0] out;

  // Clocking block and modport declaration for driver
  clocking dr_cb @(posedge clk);
    output in1;
    output in2;
    input out;
  endclocking

  modport DRV(clocking dr_cb, input clk, rst);

  // Clocking block and modport declaration for monitor
  clocking rc_cb @(negedge clk);
    input in1;
    input in2;
    input out;
  endclocking

  modport RCV(clocking rc_cb, input clk, rst);
endinterface
