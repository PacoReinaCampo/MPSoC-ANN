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

class peripheral_uvm_scoreboard extends uvm_scoreboard;
  uvm_analysis_imp #(peripheral_uvm_sequence_item, peripheral_uvm_scoreboard) item_collect_export;

  // Sequence Item method instantiation
  peripheral_uvm_sequence_item item_q [$];

  // Utility declaration
  `uvm_component_utils(peripheral_uvm_scoreboard)

  // Constructor
  function new(string name = "scoreboard", uvm_component parent = null);
    super.new(name, parent);
    item_collect_export = new("item_collect_export", this);
  endfunction

  // Build phase
  function void build_phase(uvm_phase phase);
    super.build_phase(phase);
  endfunction

  function void write(peripheral_uvm_sequence_item req);
    item_q.push_back(req);
  endfunction

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

  parameter zero = 8'h00;
  parameter one = 8'h01;

  parameter ASSIGN_VALUE = 4'b0000;
  parameter DATA_INPUT = 4'b0010;
  parameter DATA_OUTPUT = 4'b1010;
  parameter OUTPUT_VALUE = 4'b1000;
  parameter ADDITION = 4'b0100;
  parameter SUBTRACTION = 4'b0101;
  parameter JUMP = 4'b1110;
  parameter JUMP_POSITIVE = 4'b1100;
  parameter JUMP_NEGATIVE = 4'b1101;

  reg [7:0] X       [16];

  reg [7:0] in_port [16];
  reg [7:0] out_port[16];

  // Run phase
  task run_phase(uvm_phase phase);
    // Sequence Item method instantiation
    peripheral_uvm_sequence_item scoreboard_item;

    forever begin
      wait (item_q.size > 0);

      if (item_q.size > 0) begin
        scoreboard_item = item_q.pop_front();

        in_port[0]  = scoreboard_item.IN0;
        in_port[1]  = scoreboard_item.IN1;
        in_port[2]  = scoreboard_item.IN2;
        in_port[3]  = scoreboard_item.IN3;
        in_port[4]  = scoreboard_item.IN4;
        in_port[5]  = scoreboard_item.IN5;
        in_port[6]  = scoreboard_item.IN6;
        in_port[8]  = scoreboard_item.IN8;
        in_port[9]  = scoreboard_item.IN9;
        in_port[10] = scoreboard_item.IN10;
        in_port[11] = scoreboard_item.IN11;
        in_port[12] = scoreboard_item.IN12;
        in_port[13] = scoreboard_item.IN13;
        in_port[14] = scoreboard_item.IN14;
        in_port[15] = scoreboard_item.IN15;

        OUT0  = out_port[0];
        OUT1  = out_port[1];
        OUT2  = out_port[2];
        OUT3  = out_port[3];
        OUT4  = out_port[4];
        OUT5  = out_port[5];
        OUT6  = out_port[6];
        OUT7  = out_port[7];
        OUT8  = out_port[8];
        OUT9  = out_port[9];
        OUT10 = out_port[10];
        OUT11 = out_port[11];
        OUT12 = out_port[12];
        OUT13 = out_port[13];
        OUT14 = out_port[14];
        OUT15 = out_port[15];

        case (scoreboard_item.instruction[15:12])
          ASSIGN_VALUE: begin
            X[scoreboard_item.instruction[3:0]] = scoreboard_item.instruction[11:4];

            pc = pc + one;
          end
          DATA_INPUT: begin
            X[scoreboard_item.instruction[3:0]] = in_port[scoreboard_item.instruction[7:4]];

            pc = pc + one;
          end
          DATA_OUTPUT: begin
            out_port[scoreboard_item.instruction[11:8]] = X[scoreboard_item.instruction[7:4]];

            pc = pc + one;
          end
          OUTPUT_VALUE: begin
            out_port[scoreboard_item.instruction[11:8]] = scoreboard_item.instruction[7:0];

            pc = pc + one;
          end
          ADDITION: begin
            X[scoreboard_item.instruction[3:0]] = X[scoreboard_item.instruction[11:8]] + X[scoreboard_item.instruction[7:4]];

            pc = pc + one;
          end
          SUBTRACTION: begin
            X[scoreboard_item.instruction[3:0]] = X[scoreboard_item.instruction[11:8]] - X[scoreboard_item.instruction[7:4]];

            pc = pc + one;
          end
          JUMP: begin
            pc = scoreboard_item.instruction[7:0];
          end
          JUMP_POSITIVE: begin
            if (X[scoreboard_item.instruction[11:8]][7] == 1'b1 && X[scoreboard_item.instruction[11:8]] != zero) begin
              pc = scoreboard_item.instruction[7:0];
            end else begin
              pc = pc + one;
            end
          end
          JUMP_NEGATIVE: begin
            if (X[scoreboard_item.instruction[11:8]][7] == 1'b1) begin
              pc = scoreboard_item.instruction[7:0];
            end else begin
              pc = pc - one;
            end
          end
          default: begin
          end
        endcase

        $display("----------------------------------------------------------------------------------------------------------");

        if (scoreboard_item.OUT0 == OUT0) begin
          `uvm_info(get_type_name, $sformatf("Matched: OUT0 = %0d, OUT0 = %0d", scoreboard_item.OUT0, OUT0), UVM_LOW);
        end else begin
          `uvm_error(get_name, $sformatf("Dis-Matched: OUT0 = %0d, OUT0 = %0d", scoreboard_item.OUT0, OUT0));
        end

        if (scoreboard_item.OUT1 == OUT1) begin
          `uvm_info(get_type_name, $sformatf("Matched: OUT1 = %0d, OUT1 = %0d", scoreboard_item.OUT1, OUT1), UVM_LOW);
        end else begin
          `uvm_error(get_name, $sformatf("Dis-Matched: OUT1 = %0d, OUT1 = %0d", scoreboard_item.OUT1, OUT1));
        end

        if (scoreboard_item.pc == pc) begin
          `uvm_info(get_type_name, $sformatf("Matched: PC = %0d, PC = %0d", scoreboard_item.pc, pc), UVM_LOW);
        end else begin
          `uvm_error(get_name, $sformatf("Dis-Matched: PC = %0d, PC = %0d", scoreboard_item.pc, pc));
        end
        $display("----------------------------------------------------------------------------------------------------------");
      end
    end
  endtask

endclass
