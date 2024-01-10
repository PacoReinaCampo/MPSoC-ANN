class peripheral_uvm_transaction extends uvm_sequence_item;
  // Declaration of peripheral_adder transaction fields
  rand bit [15:0] instruction;

  rand bit [7:0] IN0;
  rand bit [7:0] IN1;
  rand bit [7:0] IN2;
  rand bit [7:0] IN3;
  rand bit [7:0] IN4;
  rand bit [7:0] IN5;
  rand bit [7:0] IN6;
  rand bit [7:0] IN7;
  rand bit [7:0] IN8;
  rand bit [7:0] IN9;
  rand bit [7:0] IN10;
  rand bit [7:0] IN11;
  rand bit [7:0] IN12;
  rand bit [7:0] IN13;
  rand bit [7:0] IN14;
  rand bit [7:0] IN15;

  bit [7:0] OUT0;
  bit [7:0] OUT1;
  bit [7:0] OUT2;
  bit [7:0] OUT3;
  bit [7:0] OUT4;
  bit [7:0] OUT5;
  bit [7:0] OUT6;
  bit [7:0] OUT7;
  bit [7:0] OUT8;
  bit [7:0] OUT9;
  bit [7:0] OUT10;
  bit [7:0] OUT11;
  bit [7:0] OUT12;
  bit [7:0] OUT13;
  bit [7:0] OUT14;
  bit [7:0] OUT15;

  bit [7:0] pc;

  // Declaration of Utility and Field macros
  `uvm_object_utils_begin(peripheral_uvm_transaction)

  `uvm_field_int(instruction, UVM_ALL_ON)

  `uvm_field_int(IN0, UVM_ALL_ON)
  `uvm_field_int(IN1, UVM_ALL_ON)
  `uvm_field_int(IN2, UVM_ALL_ON)
  `uvm_field_int(IN3, UVM_ALL_ON)
  `uvm_field_int(IN4, UVM_ALL_ON)
  `uvm_field_int(IN5, UVM_ALL_ON)
  `uvm_field_int(IN6, UVM_ALL_ON)
  `uvm_field_int(IN7, UVM_ALL_ON)
  `uvm_field_int(IN8, UVM_ALL_ON)
  `uvm_field_int(IN9, UVM_ALL_ON)
  `uvm_field_int(IN10, UVM_ALL_ON)
  `uvm_field_int(IN11, UVM_ALL_ON)
  `uvm_field_int(IN12, UVM_ALL_ON)
  `uvm_field_int(IN13, UVM_ALL_ON)
  `uvm_field_int(IN14, UVM_ALL_ON)
  `uvm_field_int(IN15, UVM_ALL_ON)

  `uvm_field_int(OUT0, UVM_ALL_ON)
  `uvm_field_int(OUT1, UVM_ALL_ON)
  `uvm_field_int(OUT2, UVM_ALL_ON)
  `uvm_field_int(OUT3, UVM_ALL_ON)
  `uvm_field_int(OUT4, UVM_ALL_ON)
  `uvm_field_int(OUT5, UVM_ALL_ON)
  `uvm_field_int(OUT6, UVM_ALL_ON)
  `uvm_field_int(OUT7, UVM_ALL_ON)
  `uvm_field_int(OUT8, UVM_ALL_ON)
  `uvm_field_int(OUT9, UVM_ALL_ON)
  `uvm_field_int(OUT10, UVM_ALL_ON)
  `uvm_field_int(OUT11, UVM_ALL_ON)
  `uvm_field_int(OUT12, UVM_ALL_ON)
  `uvm_field_int(OUT13, UVM_ALL_ON)
  `uvm_field_int(OUT14, UVM_ALL_ON)
  `uvm_field_int(OUT15, UVM_ALL_ON)

  `uvm_field_int(pc, UVM_ALL_ON)

  `uvm_object_utils_end

  // Constructor
  function new(string name = "peripheral_uvm_transaction");
    super.new(name);
  endfunction

  // Declaration of Constraints
  constraint instruction_c {instruction inside {[16'h0000 : 16'hFFFF]};}

  constraint in0_c {IN0 inside {[8'h00 : 8'hFF]};}
  constraint in1_c {IN1 inside {[8'h00 : 8'hFF]};}
  constraint in2_c {IN2 inside {[8'h00 : 8'hFF]};}
  constraint in3_c {IN3 inside {[8'h00 : 8'hFF]};}
  constraint in4_c {IN4 inside {[8'h00 : 8'hFF]};}
  constraint in5_c {IN5 inside {[8'h00 : 8'hFF]};}
  constraint in6_c {IN6 inside {[8'h00 : 8'hFF]};}
  constraint in7_c {IN7 inside {[8'h00 : 8'hFF]};}
  constraint in8_c {IN8 inside {[8'h00 : 8'hFF]};}
  constraint in9_c {IN9 inside {[8'h00 : 8'hFF]};}
  constraint in10_c {IN10 inside {[8'h00 : 8'hFF]};}
  constraint in11_c {IN11 inside {[8'h00 : 8'hFF]};}
  constraint in12_c {IN12 inside {[8'h00 : 8'hFF]};}
  constraint in13_c {IN13 inside {[8'h00 : 8'hFF]};}
  constraint in14_c {IN14 inside {[8'h00 : 8'hFF]};}
  constraint in15_c {IN15 inside {[8'h00 : 8'hFF]};}

  // Method name : post_randomize();
  // Description : To display transaction info after randomization
  function void post_randomize();
  endfunction
endclass
