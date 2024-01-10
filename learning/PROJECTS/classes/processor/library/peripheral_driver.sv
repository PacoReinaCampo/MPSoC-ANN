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

class peripheral_driver;
  virtual processor_if         vif;
  mailbox                generator_to_driver;
  peripheral_transaction transaction;

  function new(mailbox generator_to_driver, virtual processor_if vif);
    this.generator_to_driver = generator_to_driver;
    this.vif                 = vif;
  endfunction

  task run;
    forever begin
      // Driver to the DUT
      @(posedge vif.clk);
      @(posedge vif.clk);

      generator_to_driver.get(transaction);

      vif.instruction <= transaction.instruction;

      vif.IN0 <= transaction.IN0;
      vif.IN1 <= transaction.IN1;
      vif.IN2 <= transaction.IN2;
      vif.IN3 <= transaction.IN3;
      vif.IN4 <= transaction.IN4;
      vif.IN5 <= transaction.IN5;
      vif.IN6 <= transaction.IN6;
      vif.IN7 <= transaction.IN7;
      vif.IN8 <= transaction.IN8;
      vif.IN9 <= transaction.IN9;
      vif.IN10 <= transaction.IN10;
      vif.IN11 <= transaction.IN11;
      vif.IN12 <= transaction.IN12;
      vif.IN13 <= transaction.IN13;
      vif.IN14 <= transaction.IN14;
      vif.IN15 <= transaction.IN15;

      @(posedge vif.clk);
      @(posedge vif.clk);

      transaction.OUT0 <= vif.OUT0;
      transaction.OUT1 <= vif.OUT1;
      transaction.OUT2 <= vif.OUT2;
      transaction.OUT3 <= vif.OUT3;
      transaction.OUT4 <= vif.OUT4;
      transaction.OUT5 <= vif.OUT5;
      transaction.OUT6 <= vif.OUT6;
      transaction.OUT7 <= vif.OUT7;
      transaction.OUT8 <= vif.OUT8;
      transaction.OUT9 <= vif.OUT9;
      transaction.OUT10 <= vif.OUT10;
      transaction.OUT11 <= vif.OUT11;
      transaction.OUT12 <= vif.OUT12;
      transaction.OUT13 <= vif.OUT13;
      transaction.OUT14 <= vif.OUT14;
      transaction.OUT15 <= vif.OUT15;

      transaction.pc <= vif.pc;
    end
  endtask
endclass
