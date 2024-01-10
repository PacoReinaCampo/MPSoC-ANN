class peripheral_uvm_coverage #(type T = peripheral_uvm_transaction) extends uvm_subscriber #(T);
  // Declaration of Local fields
  peripheral_uvm_transaction cov_transaction;

  // Declaration of component utils to register with factory
  `uvm_component_utils(peripheral_uvm_coverage)

  // Functional coverage: covergroup for peripheral_adder
  covergroup peripheral_uvm_cg;
    option.per_instance = 1;
    option.goal = 100;

    peripheral_uvm_instruction: coverpoint cov_transaction.instruction {
      bins instruction_values[] = {[0 : $]};
    }

    peripheral_uvm_in0: coverpoint cov_transaction.IN0 {
      bins in0_values[] = {[0 : $]};
    }

    peripheral_uvm_in1: coverpoint cov_transaction.IN1 {
      bins in1_values[] = {[0 : $]};
    }

    peripheral_uvm_in2: coverpoint cov_transaction.IN2 {
      bins in2_values[] = {[0 : $]};
    }

    peripheral_uvm_in3: coverpoint cov_transaction.IN3 {
      bins in3_values[] = {[0 : $]};
    }

    peripheral_uvm_in4: coverpoint cov_transaction.IN4 {
      bins in4_values[] = {[0 : $]};
    }

    peripheral_uvm_in5: coverpoint cov_transaction.IN5 {
      bins in5_values[] = {[0 : $]};
    }

    peripheral_uvm_in6: coverpoint cov_transaction.IN6 {
      bins in6_values[] = {[0 : $]};
    }

    peripheral_uvm_in7: coverpoint cov_transaction.IN7 {
      bins in7_values[] = {[0 : $]};
    }

    peripheral_uvm_in8: coverpoint cov_transaction.IN8 {
      bins in8_values[] = {[0 : $]};
    }

    peripheral_uvm_in9: coverpoint cov_transaction.IN9 {
      bins in9_values[] = {[0 : $]};
    }

    peripheral_uvm_in10: coverpoint cov_transaction.IN10 {
      bins in10_values[] = {[0 : $]};
    }

    peripheral_uvm_in11: coverpoint cov_transaction.IN11 {
      bins in11_values[] = {[0 : $]};
    }

    peripheral_uvm_in12: coverpoint cov_transaction.IN12 {
      bins in12_values[] = {[0 : $]};
    }

    peripheral_uvm_in13: coverpoint cov_transaction.IN13 {
      bins in13_values[] = {[0 : $]};
    }

    peripheral_uvm_in14: coverpoint cov_transaction.IN14 {
      bins in14_values[] = {[0 : $]};
    }

    peripheral_uvm_in15: coverpoint cov_transaction.IN15 {
      bins in15_values[] = {[0 : $]};
    }

    peripheral_uvm_out0: coverpoint cov_transaction.OUT0 {
      bins out0_values[] = {[0 : $]};
    }

    peripheral_uvm_out1: coverpoint cov_transaction.OUT1 {
      bins out1_values[] = {[0 : $]};
    }

    peripheral_uvm_out2: coverpoint cov_transaction.OUT2 {
      bins out2_values[] = {[0 : $]};
    }

    peripheral_uvm_out3: coverpoint cov_transaction.OUT3 {
      bins out3_values[] = {[0 : $]};
    }

    peripheral_uvm_out4: coverpoint cov_transaction.OUT4 {
      bins out4_values[] = {[0 : $]};
    }

    peripheral_uvm_out5: coverpoint cov_transaction.OUT5 {
      bins out5_values[] = {[0 : $]};
    }

    peripheral_uvm_out6: coverpoint cov_transaction.OUT6 {
      bins out6_values[] = {[0 : $]};
    }

    peripheral_uvm_out7: coverpoint cov_transaction.OUT7 {
      bins out7_values[] = {[0 : $]};
    }

    peripheral_uvm_out8: coverpoint cov_transaction.OUT8 {
      bins out8_values[] = {[0 : $]};
    }

    peripheral_uvm_out9: coverpoint cov_transaction.OUT9 {
      bins out9_values[] = {[0 : $]};
    }

    peripheral_uvm_out10: coverpoint cov_transaction.OUT10 {
      bins out10_values[] = {[0 : $]};
    }

    peripheral_uvm_out11: coverpoint cov_transaction.OUT11 {
      bins out11_values[] = {[0 : $]};
    }

    peripheral_uvm_out12: coverpoint cov_transaction.OUT12 {
      bins out12_values[] = {[0 : $]};
    }

    peripheral_uvm_out13: coverpoint cov_transaction.OUT13 {
      bins out13_values[] = {[0 : $]};
    }

    peripheral_uvm_out14: coverpoint cov_transaction.OUT14 {
      bins out14_values[] = {[0 : $]};
    }

    peripheral_uvm_out15: coverpoint cov_transaction.OUT15 {
      bins out15_values[] = {[0 : $]};
    }

    peripheral_uvm_pc: coverpoint cov_transaction.pc {
      bins pc_values[] = {[0 : $]};
    }
  endgroup

  // Constructor
  function new(string name = "peripheral_uvm_reference_model", uvm_component parent);
    super.new(name, parent);
    peripheral_uvm_cg = new();
    cov_transaction   = new();
  endfunction

  // Method name : sample
  // Description : sampling peripheral_adder coverage
  function void write(T t);
    this.cov_transaction = t;
    peripheral_uvm_cg.sample();
  endfunction
endclass
