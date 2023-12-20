class my_monitor extends uvm_component;
  // Factory registration
  `uvm_component_utils(my_monitor)

  // External interfaces
  virtual dut_if dut_if_h;

  // Internal component handles
  my_sequencer my_sequencer_h;
  my_driver    my_driver_h;
  my_monitor   my_monitor_h;

  // Standard phase methods

  // Constructor
  function new(string name, uvm_component parent);

  // Build phase
  function void build_phase(); 

  // Connect phase
  function void connect_phase(); 

  // Start phase
  function void start_of_simulation_phase();

  // Run phase
  task run_phase(); 

  // Check phase
  function check_phase(); 

  // Report phase
  function report_phase(); 
endclass
