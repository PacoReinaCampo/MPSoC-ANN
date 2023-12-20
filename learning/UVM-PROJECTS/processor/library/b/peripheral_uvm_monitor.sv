////////////////////////////////////////////////////////////////////////////////
//                                            __ _      _     _               //
//                                           / _(_)    | |   | |              //
//                __ _ _   _  ___  ___ _ __ | |_ _  ___| | __| |              //
//               / _` | | | |/ _ \/ _ \ '_ \|  _| |/ _ \ |/ _` |              //
//              | (_| | |_| |  __/  __/ | | | | | |  __/ | (_| |              //
//               \__, |\__,_|\___|\___|_| |_|_| |_|\___|_|\__,_|              //
//                  | |                                                       //
//                  |_|                                                       //
//                                                                            //
//                                                                            //
//              Peripheral-NTM for MPSoC                                      //
//              Neural Turing Machine for MPSoC                               //
//                                                                            //
////////////////////////////////////////////////////////////////////////////////
// Copyright (c) 2022-2025 by the author(s)
//
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.
//
////////////////////////////////////////////////////////////////////////////////
// Author(s):
//   Paco Reina Campo <pacoreinacampo@queenfield.tech>

class peripheral_uvm_monitor extends uvm_monitor;
  // Virtual Interface
  virtual peripheral_design_if vif;

  // UVM analysis port
  uvm_analysis_port #(peripheral_uvm_sequence_item) item_collect_port;

  // Sequence Item method instantiation
  peripheral_uvm_sequence_item monitor_item;

  // Utility declaration
  `uvm_component_utils(peripheral_uvm_monitor)

  // Constructor
  function new(string name = "monitor", uvm_component parent = null);
    super.new(name, parent);
    item_collect_port = new("item_collect_port", this);
    monitor_item      = new();
  endfunction

  // Build phase
  function void build_phase(uvm_phase phase);
    super.build_phase(phase);
    if (!uvm_config_db#(virtual peripheral_design_if)::get(this, "", "vif", vif)) begin
      `uvm_fatal(get_type_name(), "Not set at top level");
    end
  endfunction

  // Run phase
  task run_phase(uvm_phase phase);
    forever begin
      wait (!vif.reset);
      @(posedge vif.clk);
      monitor_item.instruction = vif.instruction;

      monitor_item.IN0 = vif.IN0;
      monitor_item.IN1 = vif.IN1;
      monitor_item.IN2 = vif.IN2;
      monitor_item.IN3 = vif.IN3;
      monitor_item.IN4 = vif.IN4;
      monitor_item.IN5 = vif.IN5;
      monitor_item.IN6 = vif.IN6;
      monitor_item.IN7 = vif.IN7;
      monitor_item.IN8 = vif.IN8;
      monitor_item.IN9 = vif.IN9;
      monitor_item.IN10 = vif.IN10;
      monitor_item.IN11 = vif.IN11;
      monitor_item.IN12 = vif.IN12;
      monitor_item.IN13 = vif.IN13;
      monitor_item.IN14 = vif.IN14;
      monitor_item.IN15 = vif.IN15;

      `uvm_info(get_type_name, $sformatf("instruction = %0d", monitor_item.instruction), UVM_HIGH);

      `uvm_info(get_type_name, $sformatf("IN0 = %0d", monitor_item.IN0), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN1 = %0d", monitor_item.IN1), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN2 = %0d", monitor_item.IN2), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN3 = %0d", monitor_item.IN3), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN4 = %0d", monitor_item.IN4), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN5 = %0d", monitor_item.IN5), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN6 = %0d", monitor_item.IN6), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN7 = %0d", monitor_item.IN7), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN8 = %0d", monitor_item.IN8), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN9 = %0d", monitor_item.IN9), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN10 = %0d", monitor_item.IN10), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN11 = %0d", monitor_item.IN11), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN12 = %0d", monitor_item.IN12), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN13 = %0d", monitor_item.IN13), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN14 = %0d", monitor_item.IN14), UVM_HIGH);
      `uvm_info(get_type_name, $sformatf("IN15 = %0d", monitor_item.IN15), UVM_HIGH);

      @(posedge vif.clk);
      monitor_item.OUT0 = vif.OUT0;
      monitor_item.OUT1 = vif.OUT1;
      monitor_item.OUT2 = vif.OUT2;
      monitor_item.OUT3 = vif.OUT3;
      monitor_item.OUT4 = vif.OUT4;
      monitor_item.OUT5 = vif.OUT5;
      monitor_item.OUT6 = vif.OUT6;
      monitor_item.OUT7 = vif.OUT7;
      monitor_item.OUT8 = vif.OUT8;
      monitor_item.OUT9 = vif.OUT9;
      monitor_item.OUT10 = vif.OUT10;
      monitor_item.OUT11 = vif.OUT11;
      monitor_item.OUT12 = vif.OUT12;
      monitor_item.OUT13 = vif.OUT13;
      monitor_item.OUT14 = vif.OUT14;
      monitor_item.OUT15 = vif.OUT15;

      monitor_item.pc = vif.pc;

      item_collect_port.write(monitor_item);
    end
  endtask
endclass
