library ieee;
use ieee.std_logic_1164.all;
use ieee.numeric_std.all;

entity peripheral_adder is
  generic (
    DATA_SIZE : integer := 8
    );
  port (
    -- GLOBAL
    clk : in std_logic;
    rst : in std_logic;

    -- CONTROL
    in_valid  : in  std_logic;
    out_valid : out std_logic;

    -- DATA
    in1 : in std_logic_vector(DATA_SIZE-1 downto 0);
    in2 : in std_logic_vector(DATA_SIZE-1 downto 0);

    data_out : out std_logic_vector(DATA_SIZE downto 0)
    );
end entity;

architecture ntm_design_architecture of peripheral_adder is

  ------------------------------------------------------------------------------
  -- Types
  ------------------------------------------------------------------------------

  type adder_ctrl_fsm is (
    STARTER_STATE,                      -- STEP 0
    ENDER_STATE                         -- STEP 1
    );

  ------------------------------------------------------------------------------
  -- Constants
  ------------------------------------------------------------------------------

  constant ZERO_DATA : std_logic_vector(DATA_SIZE downto 0) := (others => '0');

  ------------------------------------------------------------------------------
  -- Signals
  ------------------------------------------------------------------------------

  -- Finite State Machine
  signal adder_ctrl_fsm_int : adder_ctrl_fsm;

begin

  ------------------------------------------------------------------------------
  -- Body
  ------------------------------------------------------------------------------

  -- data_out = in1 + in2

  -- CONTROL
  ctrl_fsm : process(clk, rst)
  begin
    if (rst = '1') then
      -- Data Outputs
      data_out <= ZERO_DATA;

      -- Control Outputs
      out_valid <= '0';

    elsif (rising_edge(clk)) then

      case adder_ctrl_fsm_int is
        when STARTER_STATE =>           -- STEP 0
          -- Control Outputs
          out_valid <= '0';

          if (in_valid = '1') then
            -- FSM Control
            adder_ctrl_fsm_int <= ENDER_STATE;
          end if;

        when ENDER_STATE =>             -- STEP 1

          -- Data Outputs
          data_out <= std_logic_vector('0' & unsigned(in1) + ('0' & unsigned(in2)));

          -- Control Outputs
          out_valid <= '1';

          -- FSM Control
          adder_ctrl_fsm_int <= STARTER_STATE;

        when others =>
          -- FSM Control
          adder_ctrl_fsm_int <= STARTER_STATE;
      end case;
    end if;
  end process;

end architecture;
