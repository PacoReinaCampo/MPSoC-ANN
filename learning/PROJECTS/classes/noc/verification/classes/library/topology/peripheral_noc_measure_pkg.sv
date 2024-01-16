package measurepkg;

// All forward declarations
typedef class packet;
typedef class flit;
typedef class packetid;
typedef class sysconfig;
typedef class trafficdesc;
typedef class poisson;
typedef class networkacc;
typedef class measurement;
typedef class environment;
typedef class trafficgen;
typedef class sink;

// Include the sources
`include "packet.sv"
`include "sysconfig.sv"
`include "poisson.sv"
`include "networkacc.sv"
`include "measurement.sv"
`include "environment.sv"
`include "trafficgen.sv"
`include "sink.sv"

`define FLIT_TYPE_PAYLOAD 2'b00
`define FLIT_TYPE_HEADER 2'b01
`define FLIT_TYPE_LAST 2'b10
`define FLIT_TYPE_SINGLE 2'b11

// Convenience definitions for mesh
`define SELECT_NONE 5'b00000
`define SELECT_NORTH 5'b00001
`define SELECT_EAST 5'b00010
`define SELECT_SOUTH 5'b00100
`define SELECT_WEST 5'b01000
`define SELECT_LOCAL 5'b10000

`define NORTH 0
`define EAST 1
`define SOUTH 2
`define WEST 3
`define LOCAL 4

typedef enum bit [4:0] {
  SELECT_NONE  = 0,
  SELECT_NORTH = 1,
  SELECT_EAST  = 2,
  SELECT_SOUTH = 4,
  SELECT_WEST  = 8,
  SELECT_LOCAL = 16
} dir_select_t;

`define NORTH 0
`define EAST 1
`define SOUTH 2
`define WEST 3
`define LOCAL 4

typedef enum bit [1:0] {
  HEADER  = 2'b01,
  SINGLE  = 2'b11,
  PAYLOAD = 2'b00,
  LAST    = 2'b10
} flit_type_t;

class flit_t #(
  int data_width = 32,
  int type_width = 2
);
  bit [type_width-1:0] ftype;
  bit [data_width-1:0] content;
endclass

class flit_header_t #(
  int data_width = 32
);
  bit [            4:0] dest;
  bit [            3:0] prio;
  bit [            2:0] packet_class;
  bit [data_width-13:0] class_specific;
endclass

endpackage // measure
