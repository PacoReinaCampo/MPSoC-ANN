`include "uvm_macros.svh"
import uvm_pkg::*;

`include "design_if.sv"
`include "peripheral_test.sv"

module peripheral_testbench;
  // Clock declaration
  reg clk;

  // Clock Generation
  always #10 clk = ~clk;

  initial begin
    clk <= 0;
  end

  // Virtual interface
  design_if _if (clk);

  // DUT instantiation
  processor dut (
    .clk(clk),

    .reset(_if.reset),

    .instruction(_if.instruction),

    .IN0 (_if.IN0),
    .IN1 (_if.IN1),
    .IN2 (_if.IN2),
    .IN3 (_if.IN3),
    .IN4 (_if.IN4),
    .IN5 (_if.IN5),
    .IN6 (_if.IN6),
    .IN7 (_if.IN7),
    .IN8 (_if.IN8),
    .IN9 (_if.IN9),
    .IN10(_if.IN10),
    .IN11(_if.IN11),
    .IN12(_if.IN12),
    .IN13(_if.IN13),
    .IN14(_if.IN14),
    .IN15(_if.IN15),

    .OUT0 (_if.OUT0),
    .OUT1 (_if.OUT1),
    .OUT2 (_if.OUT2),
    .OUT3 (_if.OUT3),
    .OUT4 (_if.OUT4),
    .OUT5 (_if.OUT5),
    .OUT6 (_if.OUT6),
    .OUT7 (_if.OUT7),
    .OUT8 (_if.OUT8),
    .OUT9 (_if.OUT9),
    .OUT10(_if.OUT10),
    .OUT11(_if.OUT11),
    .OUT12(_if.OUT12),
    .OUT13(_if.OUT13),
    .OUT14(_if.OUT14),
    .OUT15(_if.OUT15),

    .pc(_if.pc)
  );

  initial begin
    // Passing the interface handle to lower heirarchy using set method
    uvm_config_db#(virtual design_if)::set(null, "uvm_test_top", "design_if", _if);

    // Enable wave dump
    $dumpfile("system.vcd");
    $dumpvars(0);
  end

  // Calling TestCase
  initial begin
    run_test("peripheral_sample");
  end
endmodule
