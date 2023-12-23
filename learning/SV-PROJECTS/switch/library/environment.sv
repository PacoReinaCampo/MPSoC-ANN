class environment;
  // Driver handle
  driver            d0;

  // Monitor handle
  monitor           m0;

  // Generator Handle
  generator         g0;

  // Scoreboard handle
  scoreboard        s0;

  // Connect GEN -> DRV
  mailbox           drv_mbx;

  // Connect MON -> SCB
  mailbox           scb_mbx;

  // Indicates when driver is done
  event             drv_done;

  // Virtual interface handle
  virtual switch_if vif;

  function new();
    d0          = new;
    m0          = new;
    g0          = new;
    s0          = new;
    drv_mbx     = new();
    scb_mbx     = new();

    d0.drv_mbx  = drv_mbx;
    g0.drv_mbx  = drv_mbx;
    m0.scb_mbx  = scb_mbx;
    s0.scb_mbx  = scb_mbx;

    d0.drv_done = drv_done;
    g0.drv_done = drv_done;
  endfunction

  virtual task run();
    d0.vif = vif;
    m0.vif = vif;

    fork
      d0.run();
      m0.run();
      g0.run();
      s0.run();
    join_any
  endtask
endclass
