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

class peripheral_uvm_sequence_item extends uvm_sequence_item;
  // Data Signals
  rand bit [15:0] instruction;

  rand bit [7:0] IN0;
  rand bit [7:0] IN1;
  rand bit [7:0] IN2;
  rand bit [7:0] IN3;
  rand bit [7:0] IN4;
  rand bit [7:0] IN5;
  rand bit [7:0] IN6;
  rand bit [7:0] IN7;
  rand bit [7:0] IN8;
  rand bit [7:0] IN9;
  rand bit [7:0] IN10;
  rand bit [7:0] IN11;
  rand bit [7:0] IN12;
  rand bit [7:0] IN13;
  rand bit [7:0] IN14;
  rand bit [7:0] IN15;

  bit [7:0] OUT0;
  bit [7:0] OUT1;
  bit [7:0] OUT2;
  bit [7:0] OUT3;
  bit [7:0] OUT4;
  bit [7:0] OUT5;
  bit [7:0] OUT6;
  bit [7:0] OUT7;
  bit [7:0] OUT8;
  bit [7:0] OUT9;
  bit [7:0] OUT10;
  bit [7:0] OUT11;
  bit [7:0] OUT12;
  bit [7:0] OUT13;
  bit [7:0] OUT14;
  bit [7:0] OUT15;

  bit [7:0] pc;

  // Constructor
  function new(string name = "peripheral_uvm_sequence_item");
    super.new(name);
  endfunction

  // Utility and Field declarations
  `uvm_object_utils_begin(peripheral_uvm_sequence_item)

  `uvm_field_int(instruction, UVM_ALL_ON)

  `uvm_field_int(IN0, UVM_ALL_ON)
  `uvm_field_int(IN1, UVM_ALL_ON)
  `uvm_field_int(IN2, UVM_ALL_ON)
  `uvm_field_int(IN3, UVM_ALL_ON)
  `uvm_field_int(IN4, UVM_ALL_ON)
  `uvm_field_int(IN5, UVM_ALL_ON)
  `uvm_field_int(IN6, UVM_ALL_ON)
  `uvm_field_int(IN7, UVM_ALL_ON)
  `uvm_field_int(IN8, UVM_ALL_ON)
  `uvm_field_int(IN9, UVM_ALL_ON)
  `uvm_field_int(IN10, UVM_ALL_ON)
  `uvm_field_int(IN11, UVM_ALL_ON)
  `uvm_field_int(IN12, UVM_ALL_ON)
  `uvm_field_int(IN13, UVM_ALL_ON)
  `uvm_field_int(IN14, UVM_ALL_ON)
  `uvm_field_int(IN15, UVM_ALL_ON)

  `uvm_field_int(OUT0, UVM_ALL_ON)
  `uvm_field_int(OUT1, UVM_ALL_ON)
  `uvm_field_int(OUT2, UVM_ALL_ON)
  `uvm_field_int(OUT3, UVM_ALL_ON)
  `uvm_field_int(OUT4, UVM_ALL_ON)
  `uvm_field_int(OUT5, UVM_ALL_ON)
  `uvm_field_int(OUT6, UVM_ALL_ON)
  `uvm_field_int(OUT7, UVM_ALL_ON)
  `uvm_field_int(OUT8, UVM_ALL_ON)
  `uvm_field_int(OUT9, UVM_ALL_ON)
  `uvm_field_int(OUT10, UVM_ALL_ON)
  `uvm_field_int(OUT11, UVM_ALL_ON)
  `uvm_field_int(OUT12, UVM_ALL_ON)
  `uvm_field_int(OUT13, UVM_ALL_ON)
  `uvm_field_int(OUT14, UVM_ALL_ON)
  `uvm_field_int(OUT15, UVM_ALL_ON)

  `uvm_field_int(pc, UVM_ALL_ON)

  `uvm_object_utils_end

  // Constraints
  constraint ip_c {
    instruction < 65536;

    IN0 < 256;
    IN1 < 256;
    IN2 < 256;
    IN3 < 256;
    IN4 < 256;
    IN5 < 256;
    IN6 < 256;
    IN7 < 256;
    IN8 < 256;
    IN9 < 256;
    IN10 < 256;
    IN11 < 256;
    IN12 < 256;
    IN13 < 256;
    IN14 < 256;
    IN15 < 256;
  }
endclass
