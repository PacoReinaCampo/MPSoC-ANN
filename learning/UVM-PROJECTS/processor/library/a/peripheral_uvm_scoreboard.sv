class peripheral_uvm_scoreboard extends uvm_scoreboard;
  // Declaration of component utils to register with factory
  `uvm_component_utils(peripheral_uvm_scoreboard)

  // Declaration of Analysis ports and exports
  uvm_analysis_export #(peripheral_uvm_transaction) rm2scoreboard_export;
  uvm_analysis_export #(peripheral_uvm_transaction) monitor2scoreboard_export;

  uvm_tlm_analysis_fifo #(peripheral_uvm_transaction) rm2scoreboard_export_fifo;
  uvm_tlm_analysis_fifo #(peripheral_uvm_transaction) monitor2scoreboard_export_fifo;

  peripheral_uvm_transaction exp_transaction;
  peripheral_uvm_transaction act_transaction;

  peripheral_uvm_transaction exp_trans_fifo [$];
  peripheral_uvm_transaction act_trans_fifo [$];

  bit error;

  // Method name : new
  // Description : Constructor
  function new(string name, uvm_component parent);
    super.new(name, parent);
  endfunction : new

  // Method name : build phase
  // Description : Constructor
  function void build_phase(uvm_phase phase);
    super.build_phase(phase);

    rm2scoreboard_export           = new("rm2scoreboard_export", this);
    monitor2scoreboard_export      = new("monitor2scoreboard_export", this);
 
    rm2scoreboard_export_fifo      = new("rm2scoreboard_export_fifo", this);
    monitor2scoreboard_export_fifo = new("monitor2scoreboard_export_fifo", this);
  endfunction : build_phase

  // Method name : build phase
  // Description : Constructor
  function void connect_phase(uvm_phase phase);
    super.connect_phase(phase);

    rm2scoreboard_export.connect(rm2scoreboard_export_fifo.analysis_export);
    monitor2scoreboard_export.connect(monitor2scoreboard_export_fifo.analysis_export);
  endfunction : connect_phase

  // Method name : run
  // Description : comparing peripheral_adder expected and actual transactions
  virtual task run_phase(uvm_phase phase);
    super.run_phase(phase);

    forever begin
      monitor2scoreboard_export_fifo.get(act_transaction);
      if (act_transaction == null) $stop;
      act_trans_fifo.push_back(act_transaction);

      rm2scoreboard_export_fifo.get(exp_transaction);
      if (exp_transaction == null) $stop;
      exp_trans_fifo.push_back(exp_transaction);

      compare_trans();
    end
  endtask

  // Method name : compare_trans
  // Description : comparing peripheral_adder expected and actual transactions
  task compare_trans();
    peripheral_uvm_transaction exp_transaction;
    peripheral_uvm_transaction act_transaction;

    if (exp_trans_fifo.size != 0) begin
      exp_transaction = exp_trans_fifo.pop_front();
      if (act_trans_fifo.size != 0) begin
        act_transaction = act_trans_fifo.pop_front();
        `uvm_info(get_full_name(), $sformatf("expected processor OUT0 =%d , actual processor OUT0 =%d ", exp_transaction.OUT0, act_transaction.OUT0), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT1 =%d , actual processor OUT1 =%d ", exp_transaction.OUT1, act_transaction.OUT1), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT2 =%d , actual processor OUT2 =%d ", exp_transaction.OUT2, act_transaction.OUT2), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT3 =%d , actual processor OUT3 =%d ", exp_transaction.OUT3, act_transaction.OUT3), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT4 =%d , actual processor OUT4 =%d ", exp_transaction.OUT4, act_transaction.OUT4), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT5 =%d , actual processor OUT5 =%d ", exp_transaction.OUT5, act_transaction.OUT5), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT6 =%d , actual processor OUT6 =%d ", exp_transaction.OUT6, act_transaction.OUT6), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT7 =%d , actual processor OUT7 =%d ", exp_transaction.OUT7, act_transaction.OUT7), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT8 =%d , actual processor OUT8 =%d ", exp_transaction.OUT8, act_transaction.OUT8), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT9 =%d , actual processor OUT9 =%d ", exp_transaction.OUT9, act_transaction.OUT9), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT10 =%d , actual processor OUT10 =%d ", exp_transaction.OUT10, act_transaction.OUT10), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT11 =%d , actual processor OUT11 =%d ", exp_transaction.OUT11, act_transaction.OUT11), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT12 =%d , actual processor OUT12 =%d ", exp_transaction.OUT12, act_transaction.OUT12), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT13 =%d , actual processor OUT13 =%d ", exp_transaction.OUT13, act_transaction.OUT13), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT14 =%d , actual processor OUT14 =%d ", exp_transaction.OUT14, act_transaction.OUT14), UVM_LOW);
        `uvm_info(get_full_name(), $sformatf("expected processor OUT15 =%d , actual processor OUT15 =%d ", exp_transaction.OUT15, act_transaction.OUT15), UVM_LOW);

        `uvm_info(get_full_name(), $sformatf("expected processor PC =%d , actual processor PC =%d ", exp_transaction.pc, act_transaction.pc), UVM_LOW);

        if (exp_transaction.OUT0 == act_transaction.OUT0) begin
          `uvm_info(get_full_name(), $sformatf("OUT0 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT0 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT1 == act_transaction.OUT1) begin
          `uvm_info(get_full_name(), $sformatf("OUT1 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT1 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT2 == act_transaction.OUT2) begin
          `uvm_info(get_full_name(), $sformatf("OUT2 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT2 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT3 == act_transaction.OUT3) begin
          `uvm_info(get_full_name(), $sformatf("OUT3 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT3 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT4 == act_transaction.OUT4) begin
          `uvm_info(get_full_name(), $sformatf("OUT4 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT4 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT5 == act_transaction.OUT5) begin
          `uvm_info(get_full_name(), $sformatf("OUT5 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT5 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT6 == act_transaction.OUT6) begin
          `uvm_info(get_full_name(), $sformatf("OUT6 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT6 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT7 == act_transaction.OUT7) begin
          `uvm_info(get_full_name(), $sformatf("OUT7 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT7 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT8 == act_transaction.OUT8) begin
          `uvm_info(get_full_name(), $sformatf("OUT8 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT8 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT9 == act_transaction.OUT9) begin
          `uvm_info(get_full_name(), $sformatf("OUT9 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT9 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT10 == act_transaction.OUT10) begin
          `uvm_info(get_full_name(), $sformatf("OUT10 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT10 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT11 == act_transaction.OUT11) begin
          `uvm_info(get_full_name(), $sformatf("OUT11 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT11 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT12 == act_transaction.OUT12) begin
          `uvm_info(get_full_name(), $sformatf("OUT12 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT12 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT13 == act_transaction.OUT13) begin
          `uvm_info(get_full_name(), $sformatf("OUT13 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT13 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT14 == act_transaction.OUT14) begin
          `uvm_info(get_full_name(), $sformatf("OUT14 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT14 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.OUT15 == act_transaction.OUT15) begin
          `uvm_info(get_full_name(), $sformatf("OUT15 MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("OUT15 DIS-MATCHES"));
          error = 1;
        end

        if (exp_transaction.pc == act_transaction.pc) begin
          `uvm_info(get_full_name(), $sformatf("PC MATCHES"), UVM_LOW);
        end else begin
          `uvm_error(get_full_name(), $sformatf("PC DIS-MATCHES"));
          error = 1;
        end
      end
    end
  endtask

  // Method name : report
  // Description : Report the testcase status PASS/FAIL
  function void report_phase(uvm_phase phase);
    if (error == 0) begin
      $write("%c[7;32m", 27);
      $display("-------------------------------------");
      $display("------ INFO : TEST CASE PASSED ------");
      $display("-------------------------------------");
      $write("%c[0m", 27);
    end else begin
      $write("%c[7;31m", 27);
      $display("--------------------------------------");
      $display("------ ERROR : TEST CASE FAILED ------");
      $display("--------------------------------------");
    end
  endfunction
endclass : peripheral_uvm_scoreboard
