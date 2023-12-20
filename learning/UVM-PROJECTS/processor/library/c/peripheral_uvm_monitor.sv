class peripheral_uvm_monitor extends uvm_monitor;
  // Declaration of Virtual interface
  virtual peripheral_uvm_interface                vif;

  // Declaration of Analysis ports and exports
  uvm_analysis_port #(peripheral_uvm_transaction) monitor2scoreboard_port;

  // Declaration of transaction item
  peripheral_uvm_transaction                      act_transaction;

  // Declaration of component utils to register with factory
  `uvm_component_utils(peripheral_uvm_monitor)

  // Method name : new
  // Description : constructor
  function new(string name, uvm_component parent);
    super.new(name, parent);
    act_transaction         = new();
    monitor2scoreboard_port = new("monitor2scoreboard_port", this);
  endfunction : new

  // Method name : build_phase
  // Description : construct the components
  function void build_phase(uvm_phase phase);
    super.build_phase(phase);
    if (!uvm_config_db#(virtual peripheral_uvm_interface)::get(this, "", "intf", vif)) begin
      `uvm_fatal("NOVIF", {"virtual interface must be set for: ", get_full_name(), ".vif"});
    end
  endfunction : build_phase

  // Method name : run_phase
  // Description : Extract the info from DUT via interface
  virtual task run_phase(uvm_phase phase);
    forever begin
      collect_trans();
      monitor2scoreboard_port.write(act_transaction);
    end
  endtask : run_phase

  // Method name : collect_actual_trans
  // Description : run task for collecting peripheral_adder transactions
  task collect_trans();
    wait (!vif.reset);
    @(vif.rc_cb);
    @(vif.rc_cb);

    act_transaction.instruction = vif.rc_cb.instruction;

    act_transaction.IN0  = vif.rc_cb.IN0;
    act_transaction.IN1  = vif.rc_cb.IN1;
    act_transaction.IN2  = vif.rc_cb.IN2;
    act_transaction.IN3  = vif.rc_cb.IN3;
    act_transaction.IN4  = vif.rc_cb.IN4;
    act_transaction.IN5  = vif.rc_cb.IN5;
    act_transaction.IN6  = vif.rc_cb.IN6;
    act_transaction.IN7  = vif.rc_cb.IN7;
    act_transaction.IN8  = vif.rc_cb.IN8;
    act_transaction.IN9  = vif.rc_cb.IN9;
    act_transaction.IN10 = vif.rc_cb.IN10;
    act_transaction.IN11 = vif.rc_cb.IN11;
    act_transaction.IN12 = vif.rc_cb.IN12;
    act_transaction.IN13 = vif.rc_cb.IN13;
    act_transaction.IN14 = vif.rc_cb.IN14;
    act_transaction.IN15 = vif.rc_cb.IN15;

    act_transaction.OUT0  = vif.rc_cb.OUT0;
    act_transaction.OUT1  = vif.rc_cb.OUT1;
    act_transaction.OUT2  = vif.rc_cb.OUT2;
    act_transaction.OUT3  = vif.rc_cb.OUT3;
    act_transaction.OUT4  = vif.rc_cb.OUT4;
    act_transaction.OUT5  = vif.rc_cb.OUT5;
    act_transaction.OUT6  = vif.rc_cb.OUT6;
    act_transaction.OUT7  = vif.rc_cb.OUT7;
    act_transaction.OUT8  = vif.rc_cb.OUT8;
    act_transaction.OUT9  = vif.rc_cb.OUT9;
    act_transaction.OUT10 = vif.rc_cb.OUT10;
    act_transaction.OUT11 = vif.rc_cb.OUT11;
    act_transaction.OUT12 = vif.rc_cb.OUT12;
    act_transaction.OUT13 = vif.rc_cb.OUT13;
    act_transaction.OUT14 = vif.rc_cb.OUT14;
    act_transaction.OUT15 = vif.rc_cb.OUT15;

    act_transaction.pc = vif.rc_cb.pc;

    `uvm_info(get_full_name(), $sformatf("TRANSACTION FROM MONITOR"), UVM_LOW);
    act_transaction.print();
  endtask
endclass : peripheral_uvm_monitor
