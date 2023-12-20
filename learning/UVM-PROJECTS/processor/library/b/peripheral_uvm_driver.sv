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

class peripheral_uvm_driver extends uvm_driver #(peripheral_uvm_sequence_item);
  // Virtual Interface
  virtual peripheral_design_if vif;

  // Utility declaration
  `uvm_component_utils(peripheral_uvm_driver)

  // Constructor
  function new(string name = "peripheral_uvm_driver", uvm_component parent = null);
    super.new(name, parent);
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
      // Driver to the DUT
      seq_item_port.get_next_item(req);

      `uvm_info(get_type_name, $sformatf("instruction = %0d", req.instruction), UVM_LOW);

      `uvm_info(get_type_name, $sformatf("IN0 = %0d", req.IN0), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN1 = %0d", req.IN1), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN2 = %0d", req.IN2), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN3 = %0d", req.IN3), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN4 = %0d", req.IN4), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN5 = %0d", req.IN5), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN6 = %0d", req.IN6), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN7 = %0d", req.IN7), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN8 = %0d", req.IN8), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN9 = %0d", req.IN9), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN10 = %0d", req.IN10), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN11 = %0d", req.IN11), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN12 = %0d", req.IN12), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN13 = %0d", req.IN13), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN14 = %0d", req.IN14), UVM_LOW);
      `uvm_info(get_type_name, $sformatf("IN15 = %0d", req.IN15), UVM_LOW);

      vif.instruction <= req.instruction;

      vif.IN0 <= req.IN0;
      vif.IN1 <= req.IN1;
      vif.IN2 <= req.IN2;
      vif.IN3 <= req.IN3;
      vif.IN4 <= req.IN4;
      vif.IN5 <= req.IN5;
      vif.IN6 <= req.IN6;
      vif.IN7 <= req.IN7;
      vif.IN8 <= req.IN8;
      vif.IN9 <= req.IN9;
      vif.IN10 <= req.IN10;
      vif.IN11 <= req.IN11;
      vif.IN12 <= req.IN12;
      vif.IN13 <= req.IN13;
      vif.IN14 <= req.IN14;
      vif.IN15 <= req.IN15;

      seq_item_port.item_done();
    end
  endtask
endclass
