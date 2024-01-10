`include "peripheral_uvm_transaction.sv"

class peripheral_uvm_driver extends uvm_driver #(peripheral_uvm_transaction);
  // Declaration of component utils to register with factory
  peripheral_uvm_transaction       transaction;

  // Declaration of Virtual interface
  virtual peripheral_uvm_interface processor_intf;
  virtual peripheral_uvm_interface model_intf;

  // Declaration of component utils to register with factory
  `uvm_component_utils(peripheral_uvm_driver)

  uvm_analysis_port #(peripheral_uvm_transaction) driver2rm_port;

  // Method name : new
  // Description : constructor
  function new(string name, uvm_component parent);
    super.new(name, parent);
  endfunction : new

  // Method name : build_phase
  // Description : construct the components
  function void build_phase(uvm_phase phase);
    super.build_phase(phase);

    if (!uvm_config_db#(virtual peripheral_uvm_interface)::get(this, "", "processor_intf", processor_intf)) begin
      `uvm_fatal("NO_VIF", {"virtual interface must be set for: ", get_full_name(), ".processor_intf"});
    end

    if (!uvm_config_db#(virtual peripheral_uvm_interface)::get(this, "", "model_intf", model_intf)) begin
      `uvm_fatal("NO_VIF", {"virtual interface must be set for: ", get_full_name(), ".model_intf"});
    end

    driver2rm_port = new("driver2rm_port", this);
  endfunction : build_phase

  // Method name : run_phase
  // Description : Drive the transaction info to DUT
  virtual task run_phase(uvm_phase phase);
    processor_reset();
    model_reset();

    forever begin
      seq_item_port.get_next_item(req);
      processor_drive();
      model_drive();

      `uvm_info(get_full_name(), $sformatf("TRANSACTION FROM DRIVER"), UVM_LOW);

      req.print();

      @(model_intf.dr_cb);
      @(processor_intf.dr_cb);

      $cast(rsp, req.clone());
      rsp.set_id_info(req);
      driver2rm_port.write(rsp);
      seq_item_port.item_done();
      seq_item_port.put(rsp);
    end
  endtask : run_phase

  // Method name : drive
  // Description : Driving the processor inputs
  task processor_drive();
    wait (!processor_intf.reset);
    @(processor_intf.dr_cb);
    processor_intf.dr_cb.instruction <= req.instruction;

    processor_intf.dr_cb.IN0  <= req.IN0;
    processor_intf.dr_cb.IN1  <= req.IN1;
    processor_intf.dr_cb.IN2  <= req.IN2;
    processor_intf.dr_cb.IN3  <= req.IN3;
    processor_intf.dr_cb.IN4  <= req.IN4;
    processor_intf.dr_cb.IN5  <= req.IN5;
    processor_intf.dr_cb.IN6  <= req.IN6;
    processor_intf.dr_cb.IN7  <= req.IN7;
    processor_intf.dr_cb.IN8  <= req.IN8;
    processor_intf.dr_cb.IN9  <= req.IN9;
    processor_intf.dr_cb.IN10 <= req.IN10;
    processor_intf.dr_cb.IN11 <= req.IN11;
    processor_intf.dr_cb.IN12 <= req.IN12;
    processor_intf.dr_cb.IN13 <= req.IN13;
    processor_intf.dr_cb.IN14 <= req.IN14;
    processor_intf.dr_cb.IN15 <= req.IN15;
  endtask

  // Description : Driving the model inputs
  task model_drive();
    wait (!model_intf.reset);
    @(model_intf.dr_cb);
    model_intf.dr_cb.instruction <= req.instruction;

    model_intf.dr_cb.IN0  <= req.IN0;
    model_intf.dr_cb.IN1  <= req.IN1;
    model_intf.dr_cb.IN2  <= req.IN2;
    model_intf.dr_cb.IN3  <= req.IN3;
    model_intf.dr_cb.IN4  <= req.IN4;
    model_intf.dr_cb.IN5  <= req.IN5;
    model_intf.dr_cb.IN6  <= req.IN6;
    model_intf.dr_cb.IN7  <= req.IN7;
    model_intf.dr_cb.IN8  <= req.IN8;
    model_intf.dr_cb.IN9  <= req.IN9;
    model_intf.dr_cb.IN10 <= req.IN10;
    model_intf.dr_cb.IN11 <= req.IN11;
    model_intf.dr_cb.IN12 <= req.IN12;
    model_intf.dr_cb.IN13 <= req.IN13;
    model_intf.dr_cb.IN14 <= req.IN14;
    model_intf.dr_cb.IN15 <= req.IN15;
  endtask

  // Method name : reset
  // Description : Driving the processor inputs
  task processor_reset();
    processor_intf.dr_cb.instruction <= 0;

    processor_intf.dr_cb.IN0  <= 0;
    processor_intf.dr_cb.IN1  <= 0;
    processor_intf.dr_cb.IN2  <= 0;
    processor_intf.dr_cb.IN3  <= 0;
    processor_intf.dr_cb.IN4  <= 0;
    processor_intf.dr_cb.IN5  <= 0;
    processor_intf.dr_cb.IN6  <= 0;
    processor_intf.dr_cb.IN7  <= 0;
    processor_intf.dr_cb.IN8  <= 0;
    processor_intf.dr_cb.IN9  <= 0;
    processor_intf.dr_cb.IN10 <= 0;
    processor_intf.dr_cb.IN11 <= 0;
    processor_intf.dr_cb.IN12 <= 0;
    processor_intf.dr_cb.IN13 <= 0;
    processor_intf.dr_cb.IN14 <= 0;
    processor_intf.dr_cb.IN15 <= 0;
  endtask

  // Description : Driving the model inputs
  task model_reset();
    model_intf.dr_cb.instruction <= 0;

    model_intf.dr_cb.IN0  <= 0;
    model_intf.dr_cb.IN1  <= 0;
    model_intf.dr_cb.IN2  <= 0;
    model_intf.dr_cb.IN3  <= 0;
    model_intf.dr_cb.IN4  <= 0;
    model_intf.dr_cb.IN5  <= 0;
    model_intf.dr_cb.IN6  <= 0;
    model_intf.dr_cb.IN7  <= 0;
    model_intf.dr_cb.IN8  <= 0;
    model_intf.dr_cb.IN9  <= 0;
    model_intf.dr_cb.IN10 <= 0;
    model_intf.dr_cb.IN11 <= 0;
    model_intf.dr_cb.IN12 <= 0;
    model_intf.dr_cb.IN13 <= 0;
    model_intf.dr_cb.IN14 <= 0;
    model_intf.dr_cb.IN15 <= 0;
  endtask
endclass : peripheral_uvm_driver
