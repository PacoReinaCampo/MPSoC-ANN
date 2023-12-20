class peripheral_uvm_reference_model extends uvm_component;
  // Declaration of component utils to register with factory
  `uvm_component_utils(peripheral_uvm_reference_model)

  // Declaration of Local Signals
  uvm_analysis_export #(peripheral_uvm_transaction)   rm_export;
  uvm_analysis_port #(peripheral_uvm_transaction)     rm2scoreboard_port;
  peripheral_uvm_transaction                          exp_transaction;
  peripheral_uvm_transaction                          rm_transaction;
  uvm_tlm_analysis_fifo #(peripheral_uvm_transaction) rm_exp_fifo;

  // Constructor
  function new(string name = "peripheral_uvm_reference_model", uvm_component parent);
    super.new(name, parent);
  endfunction

  // Method name : build-phase
  // Description : construct the components: driver, monitor, sequencer
  function void build_phase(uvm_phase phase);
    super.build_phase(phase);
    rm_export          = new("rm_export", this);
    rm2scoreboard_port = new("rm2scoreboard_port", this);
    rm_exp_fifo        = new("rm_exp_fifo", this);
  endfunction : build_phase

  // Method name : connect_phase
  // Description : connect tlm ports ande exports (ex: analysis port/exports)
  function void connect_phase(uvm_phase phase);
    super.connect_phase(phase);
    rm_export.connect(rm_exp_fifo.analysis_export);
  endfunction : connect_phase

  // Method name : run
  // Description : Driving the dut inputs
  task run_phase(uvm_phase phase);
    forever begin
      rm_exp_fifo.get(rm_transaction);
      get_expected_transaction(rm_transaction);
    end
  endtask

  parameter zero = 8'h00;
  parameter one  = 8'h01;

  parameter ASSIGN_VALUE  = 4'b0000;
  parameter DATA_INPUT    = 4'b0010;
  parameter DATA_OUTPUT   = 4'b1010;
  parameter OUTPUT_VALUE  = 4'b1000;
  parameter ADDITION      = 4'b0100;
  parameter SUBTRACTION   = 4'b0101;
  parameter JUMP          = 4'b1110;
  parameter JUMP_POSITIVE = 4'b1100;
  parameter JUMP_NEGATIVE = 4'b1101;

  reg [7:0] X       [16];

  reg [7:0] in_port [16];
  reg [7:0] out_port[16];

  // Method name : get_expected_transaction
  // Description : Expected transaction
  task get_expected_transaction(peripheral_uvm_transaction rm_transaction);
    this.exp_transaction = rm_transaction;
    `uvm_info(get_full_name(), $sformatf("EXPECTED TRANSACTION FROM REF MODEL"), UVM_LOW);
    exp_transaction.print();

    in_port[0]            = exp_transaction.IN0;
    in_port[1]            = exp_transaction.IN1;
    in_port[2]            = exp_transaction.IN2;
    in_port[3]            = exp_transaction.IN3;
    in_port[4]            = exp_transaction.IN4;
    in_port[5]            = exp_transaction.IN5;
    in_port[6]            = exp_transaction.IN6;
    in_port[8]            = exp_transaction.IN8;
    in_port[9]            = exp_transaction.IN9;
    in_port[10]           = exp_transaction.IN10;
    in_port[11]           = exp_transaction.IN11;
    in_port[12]           = exp_transaction.IN12;
    in_port[13]           = exp_transaction.IN13;
    in_port[14]           = exp_transaction.IN14;
    in_port[15]           = exp_transaction.IN15;

    exp_transaction.OUT0  = out_port[0];
    exp_transaction.OUT1  = out_port[1];
    exp_transaction.OUT2  = out_port[2];
    exp_transaction.OUT3  = out_port[3];
    exp_transaction.OUT4  = out_port[4];
    exp_transaction.OUT5  = out_port[5];
    exp_transaction.OUT6  = out_port[6];
    exp_transaction.OUT7  = out_port[7];
    exp_transaction.OUT8  = out_port[8];
    exp_transaction.OUT9  = out_port[9];
    exp_transaction.OUT10 = out_port[10];
    exp_transaction.OUT11 = out_port[11];
    exp_transaction.OUT12 = out_port[12];
    exp_transaction.OUT13 = out_port[13];
    exp_transaction.OUT14 = out_port[14];
    exp_transaction.OUT15 = out_port[15];

    case (exp_transaction.instruction[15:12])
      ASSIGN_VALUE: begin
        X[exp_transaction.instruction[3:0]] = exp_transaction.instruction[11:4];

        exp_transaction.pc = exp_transaction.pc + one;
      end
      DATA_INPUT: begin
        X[exp_transaction.instruction[3:0]] = in_port[exp_transaction.instruction[7:4]];

        exp_transaction.pc = exp_transaction.pc + one;
      end
      DATA_OUTPUT: begin
        out_port[exp_transaction.instruction[11:8]] = X[exp_transaction.instruction[7:4]];

        exp_transaction.pc = exp_transaction.pc + one;
      end
      OUTPUT_VALUE: begin
        out_port[exp_transaction.instruction[11:8]] = exp_transaction.instruction[7:0];

        exp_transaction.pc = exp_transaction.pc + one;
      end
      ADDITION: begin
        X[exp_transaction.instruction[3:0]] = X[exp_transaction.instruction[11:8]] + X[exp_transaction.instruction[7:4]];

        exp_transaction.pc = exp_transaction.pc + one;
      end
      SUBTRACTION: begin
        X[exp_transaction.instruction[3:0]] = X[exp_transaction.instruction[11:8]] - X[exp_transaction.instruction[7:4]];

        exp_transaction.pc = exp_transaction.pc + one;
      end
      JUMP: begin
        exp_transaction.pc = exp_transaction.instruction[7:0];
      end
      JUMP_POSITIVE: begin
        if (X[exp_transaction.instruction[11:8]][7] == 1'b1 && X[exp_transaction.instruction[11:8]] != zero) begin
          exp_transaction.pc = exp_transaction.instruction[7:0];
        end else begin
          exp_transaction.pc = exp_transaction.pc + one;
        end
      end
      JUMP_NEGATIVE: begin
        if (X[exp_transaction.instruction[11:8]][7] == 1'b1) begin
          exp_transaction.pc = exp_transaction.instruction[7:0];
        end else begin
          exp_transaction.pc = exp_transaction.pc - one;
        end
      end
      default: begin
      end
    endcase

    rm2scoreboard_port.write(exp_transaction);
  endtask
endclass
