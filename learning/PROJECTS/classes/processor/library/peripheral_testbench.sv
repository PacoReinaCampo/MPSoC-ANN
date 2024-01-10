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

`include "peripheral_interface.sv"
`include "peripheral_test.sv"

module peripheral_testbench;
  bit clk;
  bit reset;

  always #2 clk = ~clk;

  processor_if vif (clk, reset);

  processor dut (
    .clk(vif.clk),

    .reset(vif.reset),

    .instruction(vif.instruction),

    .IN0 (vif.IN0),
    .IN1 (vif.IN1),
    .IN2 (vif.IN2),
    .IN3 (vif.IN3),
    .IN4 (vif.IN4),
    .IN5 (vif.IN5),
    .IN6 (vif.IN6),
    .IN7 (vif.IN7),
    .IN8 (vif.IN8),
    .IN9 (vif.IN9),
    .IN10(vif.IN10),
    .IN11(vif.IN11),
    .IN12(vif.IN12),
    .IN13(vif.IN13),
    .IN14(vif.IN14),
    .IN15(vif.IN15),

    .OUT0 (vif.OUT0),
    .OUT1 (vif.OUT1),
    .OUT2 (vif.OUT2),
    .OUT3 (vif.OUT3),
    .OUT4 (vif.OUT4),
    .OUT5 (vif.OUT5),
    .OUT6 (vif.OUT6),
    .OUT7 (vif.OUT7),
    .OUT8 (vif.OUT8),
    .OUT9 (vif.OUT9),
    .OUT10(vif.OUT10),
    .OUT11(vif.OUT11),
    .OUT12(vif.OUT12),
    .OUT13(vif.OUT13),
    .OUT14(vif.OUT14),
    .OUT15(vif.OUT15),

    .pc(vif.pc)
  );

  peripheral_test t1 (vif);

  initial begin
    clk = 0;
 
    reset = 1;
    #5;
    reset = 0;
  end

  initial begin
    // Dump waves
    $dumpfile("system.vcd");
    $dumpvars(0);
  end
endmodule
