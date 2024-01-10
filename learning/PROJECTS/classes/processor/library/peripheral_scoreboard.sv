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

class peripheral_scoreboard;
  int     compare_cnt;
  mailbox monitor_to_scoreboard;

  function new(mailbox monitor_to_scoreboard);
    this.monitor_to_scoreboard = monitor_to_scoreboard;
  endfunction

  bit [15:0] instruction;

  bit [7:0] IN0;
  bit [7:0] IN1;
  bit [7:0] IN2;
  bit [7:0] IN3;
  bit [7:0] IN4;
  bit [7:0] IN5;
  bit [7:0] IN6;
  bit [7:0] IN7;
  bit [7:0] IN8;
  bit [7:0] IN9;
  bit [7:0] IN10;
  bit [7:0] IN11;
  bit [7:0] IN12;
  bit [7:0] IN13;
  bit [7:0] IN14;
  bit [7:0] IN15;

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

  task run;
    forever begin
      peripheral_transaction transaction;
      transaction = new();
      monitor_to_scoreboard.get(transaction);

      model(
        // Data Signals
        .instruction(instruction),

        .IN0(IN0),
        .IN1(IN1),
        .IN2(IN2),
        .IN3(IN3),
        .IN4(IN4),
        .IN5(IN5),
        .IN6(IN6),
        .IN7(IN7),
        .IN8(IN8),
        .IN9(IN9),
        .IN10(IN10),
        .IN11(IN11),
        .IN12(IN12),
        .IN13(IN13),
        .IN14(IN14),
        .IN15(IN15),

        .OUT0(OUT0),
        .OUT1(OUT1),
        .OUT2(OUT2),
        .OUT3(OUT3),
        .OUT4(OUT4),
        .OUT5(OUT5),
        .OUT6(OUT6),
        .OUT7(OUT7),
        .OUT8(OUT8),
        .OUT9(OUT9),
        .OUT10(OUT10),
        .OUT11(OUT11),
        .OUT12(OUT12),
        .OUT13(OUT13),
        .OUT14(OUT14),
        .OUT15(OUT15),

        .pc(pc)
      );

      if (transaction.OUT0 == OUT0) begin
        $display("Matched: Actual OUT0 = %0d, Expected OUT0 = %0d", transaction.OUT0, OUT0);
      end else begin
        $display("Dis-Matched: Actual OUT0 = %0d, Expected OUT0 = %0d", transaction.OUT0, OUT0);
      end

      if (transaction.OUT1 == OUT1) begin
        $display("Matched: Actual OUT1 = %0d, Expected OUT1 = %0d", transaction.OUT1, OUT1);
      end else begin
        $display("Dis-Matched: Actual OUT1 = %0d, Expected OUT1 = %0d", transaction.OUT1, OUT1);
      end

      if (transaction.OUT2 == OUT2) begin
        $display("Matched: Actual OUT2 = %0d, Expected OUT2 = %0d", transaction.OUT2, OUT2);
      end else begin
        $display("Dis-Matched: Actual OUT2 = %0d, Expected OUT2 = %0d", transaction.OUT2, OUT2);
      end

      if (transaction.OUT3 == OUT3) begin
        $display("Matched: Actual OUT3 = %0d, Expected OUT3 = %0d", transaction.OUT3, OUT3);
      end else begin
        $display("Dis-Matched: Actual OUT3 = %0d, Expected OUT3 = %0d", transaction.OUT3, OUT3);
      end

      if (transaction.OUT4 == OUT4) begin
        $display("Matched: Actual OUT4 = %0d, Expected OUT4 = %0d", transaction.OUT4, OUT4);
      end else begin
        $display("Dis-Matched: Actual OUT4 = %0d, Expected OUT4 = %0d", transaction.OUT4, OUT4);
      end

      if (transaction.OUT5 == OUT5) begin
        $display("Matched: Actual OUT5 = %0d, Expected OUT5 = %0d", transaction.OUT5, OUT5);
      end else begin
        $display("Dis-Matched: Actual OUT5 = %0d, Expected OUT5 = %0d", transaction.OUT5, OUT5);
      end

      if (transaction.OUT6 == OUT6) begin
        $display("Matched: Actual OUT6 = %0d, Expected OUT6 = %0d", transaction.OUT6, OUT6);
      end else begin
        $display("Dis-Matched: Actual OUT6 = %0d, Expected OUT6 = %0d", transaction.OUT6, OUT6);
      end

      if (transaction.OUT7 == OUT7) begin
        $display("Matched: Actual OUT7 = %0d, Expected OUT7 = %0d", transaction.OUT7, OUT7);
      end else begin
        $display("Dis-Matched: Actual OUT7 = %0d, Expected OUT7 = %0d", transaction.OUT7, OUT7);
      end

      if (transaction.OUT8 == OUT8) begin
        $display("Matched: Actual OUT8 = %0d, Expected OUT8 = %0d", transaction.OUT8, OUT8);
      end else begin
        $display("Dis-Matched: Actual OUT8 = %0d, Expected OUT8 = %0d", transaction.OUT8, OUT8);
      end

      if (transaction.OUT9 == OUT9) begin
        $display("Matched: Actual OUT9 = %0d, Expected OUT9 = %0d", transaction.OUT9, OUT9);
      end else begin
        $display("Dis-Matched: Actual OUT9 = %0d, Expected OUT9 = %0d", transaction.OUT9, OUT9);
      end

      if (transaction.OUT10 == OUT10) begin
        $display("Matched: Actual OUT10 = %0d, Expected OUT10 = %0d", transaction.OUT10, OUT10);
      end else begin
        $display("Dis-Matched: Actual OUT10 = %0d, Expected OUT10 = %0d", transaction.OUT10, OUT10);
      end

      if (transaction.OUT11 == OUT11) begin
        $display("Matched: Actual OUT11 = %0d, Expected OUT11 = %0d", transaction.OUT11, OUT11);
      end else begin
        $display("Dis-Matched: Actual OUT11 = %0d, Expected OUT11 = %0d", transaction.OUT11, OUT11);
      end

      if (transaction.OUT12 == OUT12) begin
        $display("Matched: Actual OUT12 = %0d, Expected OUT12 = %0d", transaction.OUT12, OUT12);
      end else begin
        $display("Dis-Matched: Actual OUT12 = %0d, Expected OUT12 = %0d", transaction.OUT12, OUT12);
      end

      if (transaction.OUT13 == OUT13) begin
        $display("Matched: Actual OUT13 = %0d, Expected OUT13 = %0d", transaction.OUT13, OUT13);
      end else begin
        $display("Dis-Matched: Actual OUT13 = %0d, Expected OUT13 = %0d", transaction.OUT13, OUT13);
      end

      if (transaction.OUT14 == OUT14) begin
        $display("Matched: Actual OUT14 = %0d, Expected OUT14 = %0d", transaction.OUT14, OUT14);
      end else begin
        $display("Dis-Matched: Actual OUT14 = %0d, Expected OUT14 = %0d", transaction.OUT14, OUT14);
      end

      if (transaction.OUT15 == OUT15) begin
        $display("Matched: Actual OUT15 = %0d, Expected OUT15 = %0d", transaction.OUT15, OUT15);
      end else begin
        $display("Dis-Matched: Actual OUT15 = %0d, Expected OUT15 = %0d", transaction.OUT15, OUT15);
      end

      if (transaction.pc == pc) begin
        $display("Matched: Actual pc = %0d, Expected pc = %0d", transaction.pc, pc);
      end else begin
        $display("Dis-Matched: Actual pc = %0d, Expected pc = %0d", transaction.pc, pc);
      end

      compare_cnt++;
    end
  endtask

  task model;
    // Data Signals
    input [15:0] instruction;

    input [7:0] IN0;
    input [7:0] IN1;
    input [7:0] IN2;
    input [7:0] IN3;
    input [7:0] IN4;
    input [7:0] IN5;
    input [7:0] IN6;
    input [7:0] IN7;
    input [7:0] IN8;
    input [7:0] IN9;
    input [7:0] IN10;
    input [7:0] IN11;
    input [7:0] IN12;
    input [7:0] IN13;
    input [7:0] IN14;
    input [7:0] IN15;

    output [7:0] OUT0;
    output [7:0] OUT1;
    output [7:0] OUT2;
    output [7:0] OUT3;
    output [7:0] OUT4;
    output [7:0] OUT5;
    output [7:0] OUT6;
    output [7:0] OUT7;
    output [7:0] OUT8;
    output [7:0] OUT9;
    output [7:0] OUT10;
    output [7:0] OUT11;
    output [7:0] OUT12;
    output [7:0] OUT13;
    output [7:0] OUT14;
    output [7:0] OUT15;

    output [7:0] pc;

    parameter zero = 8'h00;
    parameter one  = 8'h01;

    parameter ASSIGN_VALUE  = 4'b0000;
    parameter DATA_INPUT    = 4'b0010;
    parameter DATA_OUTPUT   = 4'b1010;
    parameter OUTPUT_VALUE  = 4'b1000;
    parameter ADDITION      = 4'b0100;
    parameter SUBTRACTION   = 4'b0101;
    parameter JUMP          = 4'b1110;
    parameter JUMP_POSITIVE = 4'b1100;
    parameter JUMP_NEGATIVE = 4'b1101;

    reg [7:0] X [16];

    reg [7:0] in_port [16];
    reg [7:0] out_port[16];

    reg [7:0] counter;

    begin

      in_port[0] = IN0;
      in_port[1] = IN1;
      in_port[2] = IN2;
      in_port[3] = IN3;
      in_port[4] = IN4;
      in_port[5] = IN5;
      in_port[6] = IN6;
      in_port[8] = IN8;
      in_port[9] = IN9;
      in_port[10] = IN10;
      in_port[11] = IN11;
      in_port[12] = IN12;
      in_port[13] = IN13;
      in_port[14] = IN14;
      in_port[15] = IN15;

      OUT0 = out_port[0];
      OUT1 = out_port[1];
      OUT2 = out_port[2];
      OUT3 = out_port[3];
      OUT4 = out_port[4];
      OUT5 = out_port[5];
      OUT6 = out_port[6];
      OUT7 = out_port[7];
      OUT8 = out_port[8];
      OUT9 = out_port[9];
      OUT10 = out_port[10];
      OUT11 = out_port[11];
      OUT12 = out_port[12];
      OUT13 = out_port[13];
      OUT14 = out_port[14];
      OUT15 = out_port[15];

      pc = counter;

      case (instruction[15:12])
        ASSIGN_VALUE: begin
          X[instruction[3:0]] = instruction[11:4];

          counter             = counter + one;
        end
        DATA_INPUT: begin
          X[instruction[3:0]] = in_port[instruction[7:4]];

          counter             = counter + one;
        end
        DATA_OUTPUT: begin
          out_port[instruction[11:8]] = X[instruction[7:4]];

          counter                     = counter + one;
        end
        OUTPUT_VALUE: begin
          out_port[instruction[11:8]] = instruction[7:0];

          counter                     = counter + one;
        end
        ADDITION: begin
          X[instruction[3:0]] = X[instruction[11:8]] + X[instruction[7:4]];

          counter             = counter + one;
        end
        SUBTRACTION: begin
          X[instruction[3:0]] = X[instruction[11:8]] - X[instruction[7:4]];

          counter             = counter + one;
        end
        JUMP: begin
          counter = instruction[7:0];
        end
        JUMP_POSITIVE: begin
          if (X[instruction[11:8]][7] == 1'b1 && X[instruction[11:8]] != zero) begin
            counter = instruction[7:0];
          end else begin
            counter = counter + one;
          end
        end
        JUMP_NEGATIVE: begin
          if (X[instruction[11:8]][7] == 1'b1) begin
            counter = instruction[7:0];
          end else begin
            counter = counter - one;
          end
        end
        default: begin
        end
      endcase
    end
  endtask
endclass
