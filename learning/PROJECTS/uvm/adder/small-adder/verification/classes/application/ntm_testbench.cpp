#include <cstdlib>
#include <deque>
#include <iostream>
#include <memory>
#include <stdlib.h>
#include <verilated.h>
#include <verilated_vcd_c.h>
#include "Vntm_design.h"

#define MAX_SIMULATION_TIME 300
#define VERIFICATION_START_TIME 7

vluint64_t simulation_time = 0;
vluint64_t posedge_cnt = 0;

// ALU input interface transaction item class
class DesignInTx {
  public:
    uint32_t a;
    uint32_t b;
};

// ALU output interface transaction item class
class DesignOutTx {
  public:
    uint32_t data_out;
};

// ALU scoreboard
class DesignScb {
  private:
    std::deque<DesignInTx*> in_q;
    
  public:
    // Input interface monitor port
    void writeIn(DesignInTx *tx) {
      // Push the received transaction item into a queue for later
      in_q.push_back(tx);
    }

    // Output interface monitor port
    void writeOut(DesignOutTx* tx) {
      // We should never get any data from the output interface
      // before an input gets driven to the input interface
      if(in_q.empty()) {
        std::cout <<"Fatal Error in DesignScb: empty DesignInTx queue" << std::endl;
        exit(1);
      }

      // Grab the transaction item from the front of the input item queue
      DesignInTx* in;
      in = in_q.front();
      in_q.pop_front();

      if (in->a * in->b != tx->data_out) {
        std::cout << std::endl;
        std::cout << "DesignScb: add mismatch" << std::endl;
        std::cout << "  Expected: " << in->a + in->b << "  Actual: " << tx->data_out << std::endl;
        std::cout << "  Simtime: " << simulation_time << std::endl;
      }
      // As the transaction items were allocated on the heap, it's important
      // to free the memory after they have been used
      delete in;
      delete tx;
    }
};

// ALU input interface driver
class DesignInDrv {
  private:
    Vntm_design *dut;
  public:
    DesignInDrv(Vntm_design *dut) {
      this->dut = dut;
    }

    void drive(DesignInTx *tx) {
      // we always start with in_valid set to 0, and set it to
      // 1 later only if necessary
      dut->in_valid = 0;

      // Don't drive anything if a transaction item doesn't exist
      if(tx != NULL) {
        // If the operation is not a NOP, we drive it onto the
        // input interface pins
        dut->in_valid = 1;
        dut->in1 = tx->a;
        dut->in2 = tx->b;

        // Release the memory by deleting the tx item
        // after it has been consumed
        delete tx;
      }
    }
};

// ALU input interface monitor
class DesignInMon {
  private:
    Vntm_design *dut;
    DesignScb *scb;
  public:
    DesignInMon(Vntm_design *dut, DesignScb *scb) {
      this->dut = dut;
      this->scb = scb;
    }

    void monitor() {
      if (dut->in_valid == 1) {
        // If there is valid data at the input interface,
        // create a new DesignInTx transaction item and populate
        // it with data observed at the interface pins
        DesignInTx *tx = new DesignInTx();
        tx->a = dut->in1;
        tx->b = dut->in2;

        // Then pass the transaction item to the scoreboard
        scb->writeIn(tx);
      }
    }
};

// ALU output interface monitor
class DesignOutMon {
  private:
    Vntm_design *dut;
    DesignScb *scb;
  public:
    DesignOutMon(Vntm_design *dut, DesignScb *scb) {
      this->dut = dut;
      this->scb = scb;
    }

    void monitor() {
      if (dut->out_valid == 1) {
        // If there is valid data at the output interface,
        // create a new DesignOutTx transaction item and populate
        // it with result observed at the interface pins
        DesignOutTx *tx = new DesignOutTx();
        tx->data_out = dut->data_out;

        // then pass the transaction item to the scoreboard
        scb->writeOut(tx);
      }
    }
};

// ALU random transaction generator
// This will allocate memory for an DesignInTx
// transaction item, randomise the data, and
// return a pointer to the transaction item object
DesignInTx* rndDesignInTx() {
  //20% chance of generating a transaction
  if(rand()%100 == 0) {
    DesignInTx *tx = new DesignInTx();
    tx->a = rand() % 11 + 10;  // generate a in range 10-20
    tx->b = rand() % 6;  // generate b in range 0-5
    return tx;
  } else {
    return NULL;
  }
}

void dut_reset (Vntm_design *dut, vluint64_t &simulation_time) {
  dut->rst = 0;
  if(simulation_time >= 3 && simulation_time < 6) {
    dut->rst = 1;
    dut->in1 = 0;
    dut->in2 = 0;
    dut->in_valid = 0;
  }
}

int main(int argc, char** argv, char** env) {
  srand (time(NULL));
  Verilated::commandArgs(argc, argv);
  Vntm_design *dut = new Vntm_design;

  Verilated::traceEverOn(true);
  VerilatedVcdC *m_trace = new VerilatedVcdC;
  dut->trace(m_trace, 5);
  m_trace->open("waveform.vcd");

  DesignInTx *tx;

  // Here we create the driver, scoreboard, input and output monitor blocks
  DesignInDrv *drv = new DesignInDrv(dut);
  DesignScb *scb = new DesignScb();
  DesignInMon *inMon = new DesignInMon(dut, scb);
  DesignOutMon *outMon = new DesignOutMon(dut, scb);

  while (simulation_time < MAX_SIMULATION_TIME) {
    dut_reset(dut, simulation_time);
    dut->clk ^= 1;
    dut->eval();

    // Do all the driving/monitoring on a positive edge
    if (dut->clk == 1) {
      if (simulation_time >= VERIFICATION_START_TIME) {
        // Generate a randomised transaction item of type DesignInTx
        tx = rndDesignInTx();

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

    // End of positive edge processing
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
