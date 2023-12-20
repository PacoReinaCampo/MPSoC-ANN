`include "peripheral_uvm_scoreboard.sv"

class peripheral_uvm_entironment extends uvm_env;
  // Declaration components
  peripheral_uvm_agent      agent;
  peripheral_uvm_scoreboard scoreboard;

  // Declaration of component utils to register with factory
  `uvm_component_utils(peripheral_uvm_entironment)

  // Method name : new
  // Description : constructor
  function new(string name, uvm_component parent);
    super.new(name, parent);
  endfunction : new

  // Method name : build_phase
  // Description : constructor
  function void build_phase(uvm_phase phase);
    super.build_phase(phase);

    agent      = peripheral_uvm_agent::type_id::create("agent", this);
    scoreboard = peripheral_uvm_scoreboard::type_id::create("scoreboard", this);
  endfunction : build_phase

  // Method name : build_phase
  // Description : constructor
  function void connect_phase(uvm_phase phase);
    super.connect_phase(phase);

    agent.monitor.monitor2scoreboard_port.connect(scoreboard.monitor2scoreboard_export);
  endfunction : connect_phase
endclass : peripheral_uvm_entironment
