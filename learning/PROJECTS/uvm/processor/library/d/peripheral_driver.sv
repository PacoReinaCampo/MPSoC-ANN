class peripheral_driver extends uvm_driver #(peripheral_sequence_item);
  // Utility declaration
  `uvm_component_utils(peripheral_driver)

  // Constructor
  function new(string name = "peripheral_driver", uvm_component parent = null);
    super.new(name, parent);
  endfunction

  // Virtual Interface
  virtual design_if vif;

  // Build phase
  virtual function void build_phase(uvm_phase phase);
    super.build_phase(phase);
    if (!uvm_config_db#(virtual design_if)::get(this, "", "design_if", vif)) begin
      `uvm_fatal("DRIVER", "Could not get vif")
    end
  endfunction

  // Run phase
  virtual task run_phase(uvm_phase phase);
    super.run_phase(phase);

    forever begin
      // Sequence Item method instantiation
      peripheral_sequence_item m_item;

      `uvm_info("DRIVER", $sformatf("Wait for item from sequencer"), UVM_HIGH)
      seq_item_port.get_next_item(m_item);
      drive_item(m_item);
      seq_item_port.item_done();
    end
  endtask

  virtual task drive_item(peripheral_sequence_item m_item);
    @(vif.cb);
    vif.cb.instruction <= m_item.instruction;

    vif.cb.IN0 = m_item.IN0;
    vif.cb.IN1 = m_item.IN1;
    vif.cb.IN2 = m_item.IN2;
    vif.cb.IN3 = m_item.IN3;
    vif.cb.IN4 = m_item.IN4;
    vif.cb.IN5 = m_item.IN5;
    vif.cb.IN6 = m_item.IN6;
    vif.cb.IN7 = m_item.IN7;
    vif.cb.IN8 = m_item.IN8;
    vif.cb.IN9 = m_item.IN9;
    vif.cb.IN10 = m_item.IN10;
    vif.cb.IN11 = m_item.IN11;
    vif.cb.IN12 = m_item.IN12;
    vif.cb.IN13 = m_item.IN13;
    vif.cb.IN14 = m_item.IN14;
    vif.cb.IN15 = m_item.IN15;
  endtask
endclass
