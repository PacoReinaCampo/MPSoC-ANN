// Processor RTL verified with UVM
`include "uvm_macros.svh"
import uvm_pkg::*;

`include "peripheral_uvm_agent.sv"
`include "peripheral_uvm_environment.sv"
`include "peripheral_uvm_interface.sv"
`include "peripheral_uvm_sequence.sv"
`include "peripheral_uvm_test.sv"

module peripheral_uvm_testbench;
  // Declaration of Local Fields
  parameter cycle = 10;

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
    #(cycle * 5) reset = 0;
  end

  // Creatinng instance of interface, in order to connect DUT and testcase
  peripheral_uvm_interface processor_interface (clk, reset);

  // Creatinng instance of interface, in order to connect model and testcase
  peripheral_uvm_interface model_interface (clk, reset);

  // Processor DUT Instantation
  processor dut (
    .clk(clk),

    .reset(reset),

    .instruction(processor_interface.instruction),

    .IN0 (processor_interface.IN0),
    .IN1 (processor_interface.IN1),
    .IN2 (processor_interface.IN2),
    .IN3 (processor_interface.IN3),
    .IN4 (processor_interface.IN4),
    .IN5 (processor_interface.IN5),
    .IN6 (processor_interface.IN6),
    .IN7 (processor_interface.IN7),
    .IN8 (processor_interface.IN8),
    .IN9 (processor_interface.IN9),
    .IN10(processor_interface.IN10),
    .IN11(processor_interface.IN11),
    .IN12(processor_interface.IN12),
    .IN13(processor_interface.IN13),
    .IN14(processor_interface.IN14),
    .IN15(processor_interface.IN15),

    .OUT0 (processor_interface.OUT0),
    .OUT1 (processor_interface.OUT1),
    .OUT2 (processor_interface.OUT2),
    .OUT3 (processor_interface.OUT3),
    .OUT4 (processor_interface.OUT4),
    .OUT5 (processor_interface.OUT5),
    .OUT6 (processor_interface.OUT6),
    .OUT7 (processor_interface.OUT7),
    .OUT8 (processor_interface.OUT8),
    .OUT9 (processor_interface.OUT9),
    .OUT10(processor_interface.OUT10),
    .OUT11(processor_interface.OUT11),
    .OUT12(processor_interface.OUT12),
    .OUT13(processor_interface.OUT13),
    .OUT14(processor_interface.OUT14),
    .OUT15(processor_interface.OUT15),

    .pc(processor_interface.pc)
  );

  // Model Instantation
  model reference (
    .clk(clk),

    .reset(reset),

    .instruction(model_interface.instruction),

    .IN0 (model_interface.IN0),
    .IN1 (model_interface.IN1),
    .IN2 (model_interface.IN2),
    .IN3 (model_interface.IN3),
    .IN4 (model_interface.IN4),
    .IN5 (model_interface.IN5),
    .IN6 (model_interface.IN6),
    .IN7 (model_interface.IN7),
    .IN8 (model_interface.IN8),
    .IN9 (model_interface.IN9),
    .IN10(model_interface.IN10),
    .IN11(model_interface.IN11),
    .IN12(model_interface.IN12),
    .IN13(model_interface.IN13),
    .IN14(model_interface.IN14),
    .IN15(model_interface.IN15),

    .OUT0 (model_interface.OUT0),
    .OUT1 (model_interface.OUT1),
    .OUT2 (model_interface.OUT2),
    .OUT3 (model_interface.OUT3),
    .OUT4 (model_interface.OUT4),
    .OUT5 (model_interface.OUT5),
    .OUT6 (model_interface.OUT6),
    .OUT7 (model_interface.OUT7),
    .OUT8 (model_interface.OUT8),
    .OUT9 (model_interface.OUT9),
    .OUT10(model_interface.OUT10),
    .OUT11(model_interface.OUT11),
    .OUT12(model_interface.OUT12),
    .OUT13(model_interface.OUT13),
    .OUT14(model_interface.OUT14),
    .OUT15(model_interface.OUT15),

    .pc(model_interface.pc)
  );

  // Calling TestCase
  initial begin
    run_test();
  end

  initial begin
    // Passing the interface handle to lower heirarchy using set method
    uvm_config_db#(virtual peripheral_uvm_interface)::set(uvm_root::get(), "*", "processor_intf", processor_interface);
    uvm_config_db#(virtual peripheral_uvm_interface)::set(uvm_root::get(), "*", "model_intf", model_interface);

    // Enable wave dump
    $dumpfile("system.vcd");
    $dumpvars(0, peripheral_uvm_testbench);
  end

endmodule
