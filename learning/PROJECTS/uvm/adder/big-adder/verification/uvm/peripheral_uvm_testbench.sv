// Adder RTL verified with UVM
`include "uvm_macros.svh"
import uvm_pkg::*;

`include "peripheral_uvm_agent.sv"
`include "peripheral_uvm_coverage.sv"
`include "peripheral_uvm_environment.sv"
`include "peripheral_uvm_interface.sv"
`include "peripheral_uvm_sequence.sv"
`include "peripheral_uvm_test.sv"

module peripheral_uvm_testbench;
  // Declaration of Local Fields
  parameter CYCLE = 10;

  bit clk;

  // Clock Generation
  always #(CYCLE / 2) clk = ~clk;

  initial begin
    clk = 0;
  end

  // Creatinng instance of interface, in order to connect DUT and testcase
  peripheral_uvm_interface peripheral_uvm_intf (clk);

  // Peripheral_adder DUT Instantation
  peripheral_adder dut_instantiation (
    .clk(peripheral_uvm_intf.clk),
    .rst(peripheral_uvm_intf.rst),

    .in_valid(peripheral_uvm_intf.in_valid),
    .in1(peripheral_uvm_intf.in1),
    .in2(peripheral_uvm_intf.in2),

    .out_valid(peripheral_uvm_intf.out_valid),
    .data_out(peripheral_uvm_intf.data_out)
  );

  // Starting the execution uvm phases
  initial begin
    run_test();
  end

  initial begin
    // Set the Interface instance Using Configuration Database
    uvm_config_db#(virtual peripheral_uvm_interface)::set(uvm_root::get(), "*", "intf", peripheral_uvm_intf);

    // Enable wave dump
    $dumpfile("dump.vcd");
    $dumpvars(0);
  end

endmodule
