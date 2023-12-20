class peripheral_uvm_monitor extends uvm_monitor;
  // Declaration of Virtual interface
  virtual peripheral_uvm_interface processor_intf;
  virtual peripheral_uvm_interface model_intf;

  // Declaration of Analysis ports and exports
  uvm_analysis_port #(peripheral_uvm_transaction) monitor2scoreboard_port;

  // Declaration of transaction item
  peripheral_uvm_transaction act_transaction;
  peripheral_uvm_transaction exp_transaction;

  // Declaration of component utils to register with factory
  `uvm_component_utils(peripheral_uvm_monitor)

  // Method name : new
  // Description : constructor
  function new(string name, uvm_component parent);
    super.new(name, parent);

    act_transaction = new();
    exp_transaction = new();

    monitor2scoreboard_port = new("monitor2scoreboard_port", this);
  endfunction : new

  // Method name : build_phase
  // Description : construct the components
  function void build_phase(uvm_phase phase);
    super.build_phase(phase);

    if (!uvm_config_db#(virtual peripheral_uvm_interface)::get(this, "", "processor_intf", processor_intf)) begin
      `uvm_fatal("NOVIF", {"virtual interface must be set for: ", get_full_name(), "processor_intf"});
    end

    if (!uvm_config_db#(virtual peripheral_uvm_interface)::get(this, "", "model_intf", model_intf)) begin
      `uvm_fatal("NOVIF", {"virtual interface must be set for: ", get_full_name(), "model_intf"});
    end
  endfunction : build_phase

  // Method name : run_phase
  // Description : Extract the info from DUT via interface
  virtual task run_phase(uvm_phase phase);
    forever begin
      collect_processor_transaction();
      collect_model_transaction();

      monitor2scoreboard_port.write(act_transaction);
      monitor2scoreboard_port.write(exp_transaction);
    end
  endtask : run_phase

  // Method name : collect_actual_trans
  // Description : run task for collecting processor transactions
  task collect_processor_transaction();
    wait (!processor_intf.reset);
    @(processor_intf.rc_cb);
    @(processor_intf.rc_cb);

    act_transaction.instruction = processor_intf.rc_cb.instruction;

    act_transaction.IN0  = processor_intf.rc_cb.IN0;
    act_transaction.IN1  = processor_intf.rc_cb.IN1;
    act_transaction.IN2  = processor_intf.rc_cb.IN2;
    act_transaction.IN3  = processor_intf.rc_cb.IN3;
    act_transaction.IN4  = processor_intf.rc_cb.IN4;
    act_transaction.IN5  = processor_intf.rc_cb.IN5;
    act_transaction.IN6  = processor_intf.rc_cb.IN6;
    act_transaction.IN7  = processor_intf.rc_cb.IN7;
    act_transaction.IN8  = processor_intf.rc_cb.IN8;
    act_transaction.IN9  = processor_intf.rc_cb.IN9;
    act_transaction.IN10 = processor_intf.rc_cb.IN10;
    act_transaction.IN11 = processor_intf.rc_cb.IN11;
    act_transaction.IN12 = processor_intf.rc_cb.IN12;
    act_transaction.IN13 = processor_intf.rc_cb.IN13;
    act_transaction.IN14 = processor_intf.rc_cb.IN14;
    act_transaction.IN15 = processor_intf.rc_cb.IN15;

    `uvm_info(get_full_name(), $sformatf("TRANSACTION FROM MONITOR"), UVM_LOW);
    act_transaction.print();
  endtask

  // Description : run task for collecting model transactions
  task collect_model_transaction();
    wait (!model_intf.reset);
    @(model_intf.rc_cb);
    @(model_intf.rc_cb);

    exp_transaction.instruction = model_intf.rc_cb.instruction;

    exp_transaction.IN0  = model_intf.rc_cb.IN0;
    exp_transaction.IN1  = model_intf.rc_cb.IN1;
    exp_transaction.IN2  = model_intf.rc_cb.IN2;
    exp_transaction.IN3  = model_intf.rc_cb.IN3;
    exp_transaction.IN4  = model_intf.rc_cb.IN4;
    exp_transaction.IN5  = model_intf.rc_cb.IN5;
    exp_transaction.IN6  = model_intf.rc_cb.IN6;
    exp_transaction.IN7  = model_intf.rc_cb.IN7;
    exp_transaction.IN8  = model_intf.rc_cb.IN8;
    exp_transaction.IN9  = model_intf.rc_cb.IN9;
    exp_transaction.IN10 = model_intf.rc_cb.IN10;
    exp_transaction.IN11 = model_intf.rc_cb.IN11;
    exp_transaction.IN12 = model_intf.rc_cb.IN12;
    exp_transaction.IN13 = model_intf.rc_cb.IN13;
    exp_transaction.IN14 = model_intf.rc_cb.IN14;
    exp_transaction.IN15 = model_intf.rc_cb.IN15;

    `uvm_info(get_full_name(), $sformatf("TRANSACTION FROM MONITOR"), UVM_LOW);
    exp_transaction.print();
  endtask
endclass : peripheral_uvm_monitor
