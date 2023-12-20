interface peripheral_uvm_interface (
  input logic clk,
  input logic reset
);

  // Declaration of Signals
  logic [15:0] instruction;

  logic [7:0] IN0;
  logic [7:0] IN1;
  logic [7:0] IN2;
  logic [7:0] IN3;
  logic [7:0] IN4;
  logic [7:0] IN5;
  logic [7:0] IN6;
  logic [7:0] IN7;
  logic [7:0] IN8;
  logic [7:0] IN9;
  logic [7:0] IN10;
  logic [7:0] IN11;
  logic [7:0] IN12;
  logic [7:0] IN13;
  logic [7:0] IN14;
  logic [7:0] IN15;

  logic [7:0] OUT0;
  logic [7:0] OUT1;
  logic [7:0] OUT2;
  logic [7:0] OUT3;
  logic [7:0] OUT4;
  logic [7:0] OUT5;
  logic [7:0] OUT6;
  logic [7:0] OUT7;
  logic [7:0] OUT8;
  logic [7:0] OUT9;
  logic [7:0] OUT10;
  logic [7:0] OUT11;
  logic [7:0] OUT12;
  logic [7:0] OUT13;
  logic [7:0] OUT14;
  logic [7:0] OUT15;

  logic [7:0] pc;

  // Clocking block and modport declaration for driver
  clocking dr_cb @(posedge clk);
    output instruction;

    output IN0;
    output IN1;
    output IN2;
    output IN3;
    output IN4;
    output IN5;
    output IN6;
    output IN7;
    output IN8;
    output IN9;
    output IN10;
    output IN11;
    output IN12;
    output IN13;
    output IN14;
    output IN15;

    input OUT0;
    input OUT1;
    input OUT2;
    input OUT3;
    input OUT4;
    input OUT5;
    input OUT6;
    input OUT7;
    input OUT8;
    input OUT9;
    input OUT10;
    input OUT11;
    input OUT12;
    input OUT13;
    input OUT14;
    input OUT15;

    input pc;
  endclocking

  modport DRV(clocking dr_cb, input clk, reset);

  // Clocking block and modport declaration for monitor
  clocking rc_cb @(negedge clk);
    input instruction;

    input IN0;
    input IN1;
    input IN2;
    input IN3;
    input IN4;
    input IN5;
    input IN6;
    input IN7;
    input IN8;
    input IN9;
    input IN10;
    input IN11;
    input IN12;
    input IN13;
    input IN14;
    input IN15;

    input OUT0;
    input OUT1;
    input OUT2;
    input OUT3;
    input OUT4;
    input OUT5;
    input OUT6;
    input OUT7;
    input OUT8;
    input OUT9;
    input OUT10;
    input OUT11;
    input OUT12;
    input OUT13;
    input OUT14;
    input OUT15;

    input pc;
  endclocking

  modport RCV(clocking rc_cb, input clk, reset);
endinterface
