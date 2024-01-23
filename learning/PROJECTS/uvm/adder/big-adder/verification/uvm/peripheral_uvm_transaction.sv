class peripheral_uvm_transaction extends uvm_sequence_item;
  // Declaration of peripheral_adder transaction fields
  rand bit [7:0] in1;
  rand bit [7:0] in2;

  bit            out_valid;
  bit     [15:0] data_out;

  // Declaration of Utility and Field macros
  `uvm_object_utils_begin(peripheral_uvm_transaction)
  `uvm_field_int(in1, UVM_ALL_ON)
  `uvm_field_int(in2, UVM_ALL_ON)
  `uvm_field_int(out_valid, UVM_ALL_ON)
  `uvm_field_int(data_out, UVM_ALL_ON)
  `uvm_object_utils_end

  // Constructor
  function new(string name = "peripheral_uvm_transaction");
    super.new(name);
  endfunction

  // Declaration of Constraints
  constraint in1_c {in1 inside {[8'h00 : 8'hFF]};}
  constraint in2_c {in2 inside {[8'h00 : 8'hFF]};}

  // Method name : post_randomize();
  // Description : To display transaction info after randomization
  function void post_randomize();
  endfunction
endclass
