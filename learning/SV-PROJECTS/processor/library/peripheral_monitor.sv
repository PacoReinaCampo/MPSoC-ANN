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

class peripheral_monitor;
  virtual processor_if vif;
  mailbox        monitor_to_scoreboard;

  function new(mailbox monitor_to_scoreboard, virtual processor_if vif);
    this.vif                   = vif;
    this.monitor_to_scoreboard = monitor_to_scoreboard;
  endfunction

  task run;
    forever begin
      peripheral_transaction monitor_transaction;
      wait (!vif.reset);
      @(posedge vif.clk);
      @(posedge vif.clk);

      monitor_transaction     = new();

      monitor_transaction.instruction = vif.instruction;

      monitor_transaction.IN0 = vif.IN0;
      monitor_transaction.IN1 = vif.IN1;
      monitor_transaction.IN2 = vif.IN2;
      monitor_transaction.IN3 = vif.IN3;
      monitor_transaction.IN4 = vif.IN4;
      monitor_transaction.IN5 = vif.IN5;
      monitor_transaction.IN6 = vif.IN6;
      monitor_transaction.IN7 = vif.IN7;
      monitor_transaction.IN8 = vif.IN8;
      monitor_transaction.IN9 = vif.IN9;
      monitor_transaction.IN10 = vif.IN10;
      monitor_transaction.IN11 = vif.IN11;
      monitor_transaction.IN12 = vif.IN12;
      monitor_transaction.IN13 = vif.IN13;
      monitor_transaction.IN14 = vif.IN14;
      monitor_transaction.IN15 = vif.IN15;

      @(posedge vif.clk);
      @(posedge vif.clk);

      monitor_transaction.OUT0 = vif.OUT0;
      monitor_transaction.OUT1 = vif.OUT1;
      monitor_transaction.OUT2 = vif.OUT2;
      monitor_transaction.OUT3 = vif.OUT3;
      monitor_transaction.OUT4 = vif.OUT4;
      monitor_transaction.OUT5 = vif.OUT5;
      monitor_transaction.OUT6 = vif.OUT6;
      monitor_transaction.OUT7 = vif.OUT7;
      monitor_transaction.OUT8 = vif.OUT8;
      monitor_transaction.OUT9 = vif.OUT9;
      monitor_transaction.OUT10 = vif.OUT10;
      monitor_transaction.OUT11 = vif.OUT11;
      monitor_transaction.OUT12 = vif.OUT12;
      monitor_transaction.OUT13 = vif.OUT13;
      monitor_transaction.OUT14 = vif.OUT14;
      monitor_transaction.OUT15 = vif.OUT15;

      monitor_transaction.pc = vif.pc;

      monitor_to_scoreboard.put(monitor_transaction);
    end
  endtask
endclass
