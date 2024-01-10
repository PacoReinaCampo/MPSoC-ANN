class peripheral_monitor extends uvm_monitor;
  // Utility declaration
  `uvm_component_utils(peripheral_monitor)

  // Constructor
  function new(string name = "peripheral_monitor", uvm_component parent = null);
    super.new(name, parent);
  endfunction

  // UVM analysis port
  uvm_analysis_port #(peripheral_sequence_item) mon_analysis_port;

  // Virtual Interface
  virtual design_if vif;

  // Build phase
  virtual function void build_phase(uvm_phase phase);
    super.build_phase(phase);
    if (!uvm_config_db#(virtual design_if)::get(this, "", "design_if", vif)) begin
      `uvm_fatal("MONITOR", "Could not get vif")
    end

    mon_analysis_port = new("mon_analysis_port", this);
  endfunction

  // Run phase
  virtual task run_phase(uvm_phase phase);
    super.run_phase(phase);
    // This task monitors the interface for a complete transaction and writes into analysis port when complete
    forever begin
      @(vif.cb);
      if (vif.reset) begin
        // Create sequence item method
        peripheral_sequence_item item = peripheral_sequence_item::type_id::create("item");

        item.instruction = vif.instruction;

        item.IN0 = vif.IN0;
        item.IN1 = vif.IN1;
        item.IN2 = vif.IN2;
        item.IN3 = vif.IN3;
        item.IN4 = vif.IN4;
        item.IN5 = vif.IN5;
        item.IN6 = vif.IN6;
        item.IN7 = vif.IN7;
        item.IN8 = vif.IN8;
        item.IN9 = vif.IN9;
        item.IN10 = vif.IN10;
        item.IN11 = vif.IN11;
        item.IN12 = vif.IN12;
        item.IN13 = vif.IN13;
        item.IN14 = vif.IN14;
        item.IN15 = vif.IN15;

        item.OUT0 = vif.cb.OUT0;
        item.OUT1 = vif.cb.OUT1;
        item.OUT2 = vif.cb.OUT2;
        item.OUT3 = vif.cb.OUT3;
        item.OUT4 = vif.cb.OUT4;
        item.OUT5 = vif.cb.OUT5;
        item.OUT6 = vif.cb.OUT6;
        item.OUT7 = vif.cb.OUT7;
        item.OUT8 = vif.cb.OUT8;
        item.OUT9 = vif.cb.OUT9;
        item.OUT10 = vif.cb.OUT10;
        item.OUT11 = vif.cb.OUT11;
        item.OUT12 = vif.cb.OUT12;
        item.OUT13 = vif.cb.OUT13;
        item.OUT14 = vif.cb.OUT14;
        item.OUT15 = vif.cb.OUT15;

        item.pc = vif.cb.pc;

        mon_analysis_port.write(item);
        `uvm_info("MONITOR", $sformatf("Saw item %s", item.convert2str()), UVM_HIGH)
      end
    end
  endtask
endclass
