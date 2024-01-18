module peripheral_adder (
  // GLOBAL
  input clk,
  input rst,

  // CONTROL
  input      in_valid,
  output reg out_valid,

  // DATA
  input      [7:0] in1,
  input      [7:0] in2,

  output reg [8:0] out
);

  //////////////////////////////////////////////////////////////////////////////
  // Types
  //////////////////////////////////////////////////////////////////////////////

  parameter STARTER_STATE = 1'b0;
  parameter ENDER_STATE = 1'b1;

  //////////////////////////////////////////////////////////////////////////////
  // Constants
  //////////////////////////////////////////////////////////////////////////////

  parameter ZERO_DATA = 0;

  //////////////////////////////////////////////////////////////////////////////
  // Signals
  //////////////////////////////////////////////////////////////////////////////

  // Finite State Machine
  reg  adder_ctrl_fsm_int;

  //////////////////////////////////////////////////////////////////////////////
  // Body
  //////////////////////////////////////////////////////////////////////////////

  // CONTROL
  always @(posedge clk or posedge rst) begin
    if (rst == 1'b1) begin
      // Data Outputs
      out                <= ZERO_DATA;

      // Control Outputs
      out_valid          <= 1'b0;

      // FSM Control
      adder_ctrl_fsm_int <= STARTER_STATE;

    end else begin
      case (adder_ctrl_fsm_int)
        STARTER_STATE: begin  // STEP 0
          // Control Outputs
          out_valid <= 1'b0;

          if (in_valid == 1'b1) begin
            // FSM Control
            adder_ctrl_fsm_int <= ENDER_STATE;
          end
        end
        ENDER_STATE: begin  // STEP 1

          // Data Outputs
          out                <= in1 + in2;

          // Control Outputs
          out_valid          <= 1'b1;

          // FSM Control
          adder_ctrl_fsm_int <= STARTER_STATE;
        end
        default: begin
          // FSM Control
          adder_ctrl_fsm_int <= STARTER_STATE;
        end
      endcase
    end
  end

endmodule
