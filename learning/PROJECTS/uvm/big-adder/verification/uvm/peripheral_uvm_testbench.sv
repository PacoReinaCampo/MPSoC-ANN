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
  bit rst;
  bit in_valid;

  // Clock Generation
  initial begin
    clk = 0;
    forever #(CYCLE / 2) clk = ~clk;
  end

  // Reset Generation
  // Change may required while generating reset for synchronous/Asynchronous or Active low/Active high
  initial begin
    rst = 1;
    #(CYCLE * 2.5) rst = 0;
  end

  // Start generation
  initial begin
    in_valid = 0;
    #(CYCLE * 2.5);
    
    forever begin
      in_valid = 1;
      #CYCLE;
      in_valid = 0;
      #CYCLE;
    end
  end

  // Creatinng instance of interface, in order to connect DUT and testcase
  peripheral_uvm_interface peripheral_uvm_intf (
    clk,
    rst,
    in_valid
  );

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

  // Set the Interface instance Using Configuration Database
  initial begin
    uvm_config_db#(virtual peripheral_uvm_interface)::set(uvm_root::get(), "*", "intf", peripheral_uvm_intf);

    // Enable wave dump
    $dumpfile("dump.vcd");
    $dumpvars(0);
  end

endmodule
