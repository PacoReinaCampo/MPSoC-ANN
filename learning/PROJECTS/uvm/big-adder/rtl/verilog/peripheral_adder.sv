module peripheral_adder (
  // GLOBAL
  input wire clk,
  input wire rst,

  // CONTROL
  input      in_valid,
  output reg out_valid,

  // DATA
  input [7:0] in1,
  input [7:0] in2,

  output reg [15:0] data_out
);

  //////////////////////////////////////////////////////////////////////////////
  // Types
  //////////////////////////////////////////////////////////////////////////////

  parameter STARTER_STATE = 1'b0;
  parameter ENDER_STATE = 1'b1;

  //////////////////////////////////////////////////////////////////////////////
  // Constants
  //////////////////////////////////////////////////////////////////////////////

  parameter ZERO_SDATA = 0;
  parameter ONE_SDATA  = 1;

  //////////////////////////////////////////////////////////////////////////////
  // Signals
  //////////////////////////////////////////////////////////////////////////////

  // Finite State Machine
  reg multiplier_ctrl_fsm_int;

  // Data Internal
  logic [15:0] multiplier_int;

  // Control Internal
  logic [7:0] index_loop;

  always @(posedge clk or posedge rst) begin
    if (rst) begin
      // Data Outputs
      data_out <= ZERO_SDATA;

      // Control Outputs
      out_valid <= 1'b0;

      // Data Internal
      multiplier_int <= ZERO_SDATA;

      // Control Internal
      index_loop <= ZERO_SDATA;
    end else begin
      case (multiplier_ctrl_fsm_int)
        STARTER_STATE: begin  // STEP 0
          // Control Outputs
          out_valid <= 1'b0;

          if (in_valid) begin
            // Data Internal
            multiplier_int <= ZERO_SDATA;

            // Control Internal
            index_loop <= ZERO_SDATA;

            // FSM Control
            multiplier_ctrl_fsm_int <= ENDER_STATE;
          end
        end
        ENDER_STATE: begin  // STEP 1
          if (in2[7]) begin
            if (index_loop == in2) begin
              // Data Outputs
              data_out <= multiplier_int;

              // Control Outputs
              out_valid <= 1'b1;

              // FSM Control
              multiplier_ctrl_fsm_int <= STARTER_STATE;
            end else begin
              // Data Internal
              multiplier_int <= multiplier_int - ({8'h00, in1});

              // Control Internal
              index_loop <= index_loop - ONE_SDATA;
            end
          end else begin
            if (index_loop == in2) begin
              // Data Outputs
              data_out <= multiplier_int;

              // Control Outputs
              out_valid <= 1'b1;

              // FSM Control
              multiplier_ctrl_fsm_int <= STARTER_STATE;
            end else begin
              // Data Internal
              multiplier_int <= multiplier_int + ({8'h00, in1});

              // Control Internal
              index_loop <= index_loop + ONE_SDATA;
            end
          end
        end
        default: begin
          // FSM Control
          multiplier_ctrl_fsm_int <= STARTER_STATE;
        end
      endcase
    end
  end

endmodule
