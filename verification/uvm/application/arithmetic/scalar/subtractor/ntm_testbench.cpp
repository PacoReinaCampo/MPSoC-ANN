///////////////////////////////////////////////////////////////////////////////////
//                                            __ _      _     _                  //
//                                           / _(_)    | |   | |                 //
//                __ _ _   _  ___  ___ _ __ | |_ _  ___| | __| |                 //
//               / _` | | | |/ _ \/ _ \ '_ \|  _| |/ _ \ |/ _` |                 //
//              | (_| | |_| |  __/  __/ | | | | | |  __/ | (_| |                 //
//               \__, |\__,_|\___|\___|_| |_|_| |_|\___|_|\__,_|                 //
//                  | |                                                          //
//                  |_|                                                          //
//                                                                               //
//                                                                               //
//              Peripheral-NTM for MPSoC                                         //
//              Neural Turing Machine for MPSoC                                  //
//                                                                               //
///////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////
//                                                                               //
// Copyright (c) 2020-2021 by the author(s)                                      //
//                                                                               //
// Permission is hereby granted, free of charge, to any person obtaining a copy  //
// of this software and associated documentation files (the "Software"), to deal //
// in the Software without restriction, including without limitation the rights  //
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell     //
// copies of the Software, and to permit persons to whom the Software is         //
// furnished to do so, subject to the following conditions:                      //
//                                                                               //
// The above copyright notice and this permission notice shall be included in    //
// all copies or substantial portions of the Software.                           //
//                                                                               //
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR    //
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,      //
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE   //
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER        //
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, //
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN     //
// THE SOFTWARE.                                                                 //
//                                                                               //
// ============================================================================= //
// Author(s):                                                                    //
//   Paco Reina Campo <pacoreinacampo@queenfield.tech>                           //
//                                                                               //
///////////////////////////////////////////////////////////////////////////////////

#include <cstdlib>
#include <deque>
#include <iostream>
#include <memory>
#include <stdlib.h>
#include <verilated.h>
#include <verilated_vcd_c.h>
#include "Vmodel_scalar_integer_adder.h"

#define MAX_SIMULATION_TIME 300
#define VERIFICATION_START_TIME 7

vluint64_t simulation_time = 0;
vluint64_t posedge_cnt = 0;

// Scalar Subtractor input interface transaction item class
class ScalarSubtractorInTx {
  public:
    uint32_t a;
    uint32_t b;

    enum ControlOperation {
      add = 0,
      sub = 1,
      nop = 2,
    } control_operation;
};

// Scalar Subtractor output interface transaction item class
class ScalarSubtractorOutTx {
  public:
    uint32_t out;
};

// Scalar Subtractor ScoreBoard
class ScalarSubtractorScb {
  private:
    std::deque<ScalarSubtractorInTx*> in_q;
    
  public:
    // Input interface monitor port
    void writeIn(ScalarSubtractorInTx *tx){
      // Push the received transaction item into a queue for later
      in_q.push_back(tx);
    }

    // Output interface monitor port
    void writeOut(ScalarSubtractorOutTx* tx){
      // We should never get any data from the output interface
      // before an input gets driven to the input interface
      if(in_q.empty()){
        std::cout <<"Fatal Error in ScalarSubtractorScb: empty ScalarSubtractorInTx queue" << std::endl;
        exit(1);
      }

      // Grab the transaction item from the front of the input item queue
      ScalarSubtractorInTx* in;
      in = in_q.front();
      in_q.pop_front();

      switch(in->control_operation){
        // A valid signal should not be created at the output when there is no operation,
        // so we should never get a transaction item where the operation is NOP
        case ScalarSubtractorInTx::nop :
          std::cout << "Fatal error in ScalarSubtractorScb, received NOP on input" << std::endl;
          exit(1);
          break;

        // Received transaction is add
        case ScalarSubtractorInTx::add :
          if (in->a + in->b != tx->out) {
            std::cout << std::endl;
            std::cout << "ScalarSubtractorScb: add mismatch" << std::endl;
            std::cout << "  Expected: " << in->a + in->b << "  Actual: " << tx->out << std::endl;
            std::cout << "  Simtime: " << simulation_time << std::endl;
          }
          break;

        // Received transaction is sub
        case ScalarSubtractorInTx::sub :
          if (in->a - in->b != tx->out) {
            std::cout << std::endl;
            std::cout << "ScalarSubtractorScb: sub mismatch" << std::endl;
            std::cout << "  Expected: " << in->a - in->b << "  Actual: " << tx->out << std::endl;
            std::cout << "  Simtime: " << simulation_time << std::endl;
          }
          break;
      }
      // As the transaction items were allocated on the heap, it's important
      // to free the memory after they have been used
      delete in;
      delete tx;
    }
};

// ALU input interface driver
class ScalarSubtractorInDrv {
  private:
    Vmodel_scalar_integer_adder *dut;
  public:
    ScalarSubtractorInDrv(Vmodel_scalar_integer_adder *dut){
      this->dut = dut;
    }

    void drive(ScalarSubtractorInTx *tx){
      // we always start with START set to 0, and set it to
      // 1 later only if necessary
      dut->START = 0;

      // Don't drive anything if a transaction item doesn't exist
      if(tx != NULL){
        if (tx->control_operation != ScalarSubtractorInTx::nop) {
          // If the operation is not a NOP, we drive it onto the
          // input interface pins
          dut->START = 1;
          dut->OPERATION = tx->control_operation;
          dut->DATA_A_IN = tx->a;
          dut->DATA_B_IN = tx->b;
        }
        // Release the memory by deleting the tx item
        // after it has been consumed
        delete tx;
      }
    }
};

// ALU input interface monitor
class ScalarSubtractorInMon {
  private:
    Vmodel_scalar_integer_adder *dut;
    ScalarSubtractorScb *scb;
  public:
    ScalarSubtractorInMon(Vmodel_scalar_integer_adder *dut, ScalarSubtractorScb *scb){
      this->dut = dut;
      this->scb = scb;
    }

    void monitor(){
      if (dut->START == 1) {
        // If there is valid data at the input interface,
        // create a new ScalarSubtractorInTx transaction item and populate
        // it with data observed at the interface pins
        ScalarSubtractorInTx *tx = new ScalarSubtractorInTx();
        tx->control_operation = ScalarSubtractorInTx::ControlOperation(dut->OPERATION);
        tx->a = dut->DATA_A_IN;
        tx->b = dut->DATA_B_IN;

        // then pass the transaction item to the scoreboard
        scb->writeIn(tx);
      }
    }
};

// ALU output interface monitor
class ScalarSubtractorOutMon {
  private:
    Vmodel_scalar_integer_adder *dut;
    ScalarSubtractorScb *scb;
  public:
    ScalarSubtractorOutMon(Vmodel_scalar_integer_adder *dut, ScalarSubtractorScb *scb){
      this->dut = dut;
      this->scb = scb;
    }

    void monitor(){
      if (dut->READY == 1) {
        // If there is valid data at the output interface,
        // create a new ScalarSubtractorOutTx transaction item and populate
        // it with result observed at the interface pins
        ScalarSubtractorOutTx *tx = new ScalarSubtractorOutTx();
        tx->out = dut->DATA_OUT;

        // then pass the transaction item to the scoreboard
        scb->writeOut(tx);
      }
    }
};

// ALU random transaction generator
// This will allocate memory for an ScalarSubtractorInTx
// transaction item, randomise the data, and
// return a pointer to the transaction item object
ScalarSubtractorInTx* rndScalarSubtractorInTx(){
  //20% chance of generating a transaction
  if(rand()%5 == 0){
    ScalarSubtractorInTx *tx = new ScalarSubtractorInTx();
    tx->control_operation = ScalarSubtractorInTx::ControlOperation(rand() % 3);  // Our ENUM only has entries with values 0, 1, 2
    tx->a = rand() % 11 + 10;  // generate a in range 10-20
    tx->b = rand() % 6;  // generate b in range 0-5
    return tx;
  } else {
    return NULL;
  }
}

void dut_reset (Vmodel_scalar_integer_adder *dut, vluint64_t &simulation_time){
  dut->RST = 0;
  if(simulation_time >= 3 && simulation_time < 6){
    dut->RST = 1;
    dut->DATA_A_IN = 0;
    dut->DATA_B_IN = 0;
    dut->OPERATION = 0;
    dut->START = 0;
  }
}

int main(int argc, char** argv, char** env) {
  srand (time(NULL));
  Verilated::commandArgs(argc, argv);
  Vmodel_scalar_integer_adder *dut = new Vmodel_scalar_integer_adder;

  Verilated::traceEverOn(true);
  VerilatedVcdC *m_trace = new VerilatedVcdC;
  dut->trace(m_trace, 5);
  m_trace->open("waveform.vcd");

  ScalarSubtractorInTx *tx;

  // Here we create the driver, scoreboard, input and output monitor blocks
  ScalarSubtractorInDrv *drv = new ScalarSubtractorInDrv(dut);
  ScalarSubtractorScb *scb = new ScalarSubtractorScb();
  ScalarSubtractorInMon *inMon = new ScalarSubtractorInMon(dut, scb);
  ScalarSubtractorOutMon *outMon = new ScalarSubtractorOutMon(dut, scb);

  while (simulation_time < MAX_SIMULATION_TIME) {
    dut_reset(dut, simulation_time);
    dut->CLK ^= 1;
    dut->eval();

    // Do all the driving/monitoring on a positive edge
    if (dut->CLK == 1){

      if (simulation_time >= VERIFICATION_START_TIME) {
        // Generate a randomised transaction item of type ScalarSubtractorInTx
        tx = rndScalarSubtractorInTx();

        // Pass the transaction item to the ALU input interface driver,
        // which drives the input interface based on the info in the
        // transaction item
        drv->drive(tx);

        // Monitor the input interface
        inMon->monitor();

        // Monitor the output interface
        outMon->monitor();
      }
    }
    // end of positive edge processing

    m_trace->dump(simulation_time);
    simulation_time++;
  }

  m_trace->close();
  delete dut;
  delete outMon;
  delete inMon;
  delete scb;
  delete drv;
  exit(EXIT_SUCCESS);
}
