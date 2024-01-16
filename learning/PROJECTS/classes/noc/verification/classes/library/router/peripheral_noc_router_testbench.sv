`timescale 1 ns / 1 ps
`include "lisnoc.svh"

`define DATA_WIDTH 32
`define TYPE_WIDTH 2

//**************************************************************
// Constraints to create random test patters
//**************************************************************
class packet;
  integer                      vchannels;

  // packet source
  bit                   [ 4:0] self;

  rand bit              [ 4:0] dest;
  rand bit              [31:0] payload [];
  rand integer                 vc;
  rand bit              [ 3:0] prio;

  rand integer unsigned        burst_delay;

  constraint valid_dest {
    dest >= 0;
    dest < 5;
    dest != self;
  }
  constraint len_lim {
    payload.size > 0;
    payload.size <= 32;
  }
  constraint valid_vc {
    vc >= 0;
    vc < vchannels;
  }
  constraint dist_burst_delay {
    burst_delay dist {
      0  := 80,
      4  := 10,
      10 := 10
    };
  }
  constraint dist_prio {
    prio dist {
      4'b0000 := 1,
      4'b1000 := 1,
      4'b1100 := 1,
      4'b1110 := 1,
      4'b1111 := 1
    };
  }
endclass  // packet

class randomdelay;  // to delay receive acceptance
  rand integer unsigned delay;
  constraint dist_delay {
    delay dist {
      0  := 80,
      4  := 10,
      10 := 10
    };
  }
endclass  // randomdelay

//**************************************************************
// contraints declaration until here
//**************************************************************

typedef struct {
  flit_t #(`DATA_WIDTH, `TYPE_WIDTH) flit;
  time                               timestamp;
} sent_flit;

module tb_noc_router ();

  reg clk;
  reg rst;

  parameter vchannels = 2;
  parameter use_prio = 1;

  lisnoc_link_if #(.vchannels(vchannels)) north_in ();
  lisnoc_link_if #(.vchannels(vchannels)) north_out ();
  lisnoc_link_if #(.vchannels(vchannels)) east_in ();
  lisnoc_link_if #(.vchannels(vchannels)) east_out ();
  lisnoc_link_if #(.vchannels(vchannels)) south_in ();
  lisnoc_link_if #(.vchannels(vchannels)) south_out ();
  lisnoc_link_if #(.vchannels(vchannels)) west_in ();
  lisnoc_link_if #(.vchannels(vchannels)) west_out ();
  lisnoc_link_if #(.vchannels(vchannels)) local_in ();
  lisnoc_link_if #(.vchannels(vchannels)) local_out ();

  // Router interface
  lisnoc_router_2dgrid_sv #(
    .vchannels(vchannels),
    .use_prio (use_prio)
  ) uut (
    // Interfaces
    .north_out(north_out),
    .north_in (north_in),
    .east_out (east_out),
    .east_in  (east_in),
    .south_out(south_out),
    .south_in (south_in),
    .west_out (west_out),
    .west_in  (west_in),
    .local_out(local_out),
    .local_in (local_in),
    // Inputs
    .clk      (clk),
    .rst      (rst)
  );

  defparam uut.num_dests = 5;
  defparam uut.lookup = {SELECT_NORTH, SELECT_EAST, SELECT_SOUTH, SELECT_WEST, SELECT_LOCAL};

  initial begin
    clk = 0;
    rst = 1;
    #10 rst = 0;
  end

  always clk = #5 ~clk;

  testbench_if #(
    .vchannels(vchannels)
  ) tb_if (
    // Interfaces
    .north_in (north_in),
    .north_out(north_out),
    .east_in  (east_in),
    .east_out (east_out),
    .south_in (south_in),
    .south_out(south_out),
    .west_in  (west_in),
    .west_out (west_out),
    .local_in (local_in),
    .local_out(local_out),
    // Inputs
    .clk      (clk)
  );

  tb_noc_router_test #(
    .vchannels(vchannels)
  ) test (
    // Interfaces
    .tb_if(tb_if),
    // Inputs
    .clk  (clk),
    .rst  (rst)
  );

endmodule  // tb_noc_router
