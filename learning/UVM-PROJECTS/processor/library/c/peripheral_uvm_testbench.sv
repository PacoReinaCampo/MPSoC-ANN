// Processor RTL verified with UVM
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
  parameter cycle = 2;

  bit clk;
  bit reset;

  // Clock generation
  initial begin
    clk = 0;
    forever #(cycle / 2) clk = ~clk;
  end

  // Reset Generation
  // Change may required while generating reset for synchronous/Asynchronous or Active low/Active high
  initial begin
    reset = 1;
    #(cycle * 2) reset = 0;
  end

  // Creatinng instance of interface, in order to connect DUT and testcase
  peripheral_uvm_interface peripheral_uvm_intf (clk, reset);

  // Peripheral_adder DUT Instantation
  processor dut (
    .clk(clk),

    .reset(reset),

    .instruction(peripheral_uvm_intf.instruction),

    .IN0 (peripheral_uvm_intf.IN0),
    .IN1 (peripheral_uvm_intf.IN1),
    .IN2 (peripheral_uvm_intf.IN2),
    .IN3 (peripheral_uvm_intf.IN3),
    .IN4 (peripheral_uvm_intf.IN4),
    .IN5 (peripheral_uvm_intf.IN5),
    .IN6 (peripheral_uvm_intf.IN6),
    .IN7 (peripheral_uvm_intf.IN7),
    .IN8 (peripheral_uvm_intf.IN8),
    .IN9 (peripheral_uvm_intf.IN9),
    .IN10(peripheral_uvm_intf.IN10),
    .IN11(peripheral_uvm_intf.IN11),
    .IN12(peripheral_uvm_intf.IN12),
    .IN13(peripheral_uvm_intf.IN13),
    .IN14(peripheral_uvm_intf.IN14),
    .IN15(peripheral_uvm_intf.IN15),

    .OUT0 (peripheral_uvm_intf.OUT0),
    .OUT1 (peripheral_uvm_intf.OUT1),
    .OUT2 (peripheral_uvm_intf.OUT2),
    .OUT3 (peripheral_uvm_intf.OUT3),
    .OUT4 (peripheral_uvm_intf.OUT4),
    .OUT5 (peripheral_uvm_intf.OUT5),
    .OUT6 (peripheral_uvm_intf.OUT6),
    .OUT7 (peripheral_uvm_intf.OUT7),
    .OUT8 (peripheral_uvm_intf.OUT8),
    .OUT9 (peripheral_uvm_intf.OUT9),
    .OUT10(peripheral_uvm_intf.OUT10),
    .OUT11(peripheral_uvm_intf.OUT11),
    .OUT12(peripheral_uvm_intf.OUT12),
    .OUT13(peripheral_uvm_intf.OUT13),
    .OUT14(peripheral_uvm_intf.OUT14),
    .OUT15(peripheral_uvm_intf.OUT15),

    .pc(peripheral_uvm_intf.pc)
  );

  initial begin
    // Passing the interface handle to lower heirarchy using set method
    uvm_config_db#(virtual peripheral_uvm_interface)::set(uvm_root::get(), "*", "intf", peripheral_uvm_intf);

    // Enable wave dump
    $dumpfile("system.vcd");
    $dumpvars(0);
  end

  // Calling TestCase
  initial begin
    run_test();
  end

endmodule
