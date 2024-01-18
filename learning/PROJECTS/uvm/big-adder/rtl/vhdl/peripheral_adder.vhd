library ieee;
use ieee.std_logic_1164.all;
use ieee.numeric_std.all;

entity peripheral_adder is
  port (
    -- GLOBAL
    clk : in std_logic;
    rst : in std_logic;

    -- CONTROL
    in_valid  : in  std_logic;
    out_valid : out std_logic;

    -- DATA
    in1 : in std_logic_vector(7 downto 0);
    in2 : in std_logic_vector(7 downto 0);

    data_out : out std_logic_vector(15 downto 0)
    );
end entity;

architecture peripheral_adder_architecture of peripheral_adder is

  ------------------------------------------------------------------------------
  -- Types
  ------------------------------------------------------------------------------

  type multiplier_ctrl_fsm is (
    STARTER_STATE,                      -- STEP 0
    ENDER_STATE                         -- STEP 1
    );

  ------------------------------------------------------------------------------
  -- Constants
  ------------------------------------------------------------------------------

  constant ZERO_SDATA : std_logic_vector(7 downto 0) := std_logic_vector(to_signed(0, 8));
  constant ONE_SDATA  : std_logic_vector(7 downto 0) := std_logic_vector(to_signed(1, 8));

  ------------------------------------------------------------------------------
  -- Signals
  ------------------------------------------------------------------------------

  -- Finite State Machine
  signal multiplier_ctrl_fsm_int : multiplier_ctrl_fsm;

  -- Data Internal
  signal multiplier_int : std_logic_vector(15 downto 0);

  -- Control Internal
  signal index_loop : std_logic_vector(7 downto 0);

begin

  ------------------------------------------------------------------------------
  -- Body
  ------------------------------------------------------------------------------

  -- data_out = in1 · in2

  -- CONTROL
  ctrl_fsm : process(clk, rst)
  begin
    if (rst = '1') then
      -- Data Outputs
      data_out <= std_logic_vector(to_signed(0, 16));

      -- Control Outputs
      out_valid <= '0';

      -- Data Internal
      multiplier_int <= std_logic_vector(to_signed(0, 16));

      -- Control Internal
      index_loop <= ZERO_SDATA;

    elsif (rising_edge(clk)) then

      case multiplier_ctrl_fsm_int is
        when STARTER_STATE =>           -- STEP 0
          -- Control Outputs
          out_valid <= '0';

          if (in_valid = '1') then
            -- Data Internal
            multiplier_int <= std_logic_vector(to_signed(0, 16));

            -- Control Internal
            index_loop <= ZERO_SDATA;

            -- FSM Control
            multiplier_ctrl_fsm_int <= ENDER_STATE;
          end if;

        when ENDER_STATE =>             -- STEP 1

          if (in2(7) = '1') then
            if (signed(index_loop) = signed(in2)) then
              -- Data Outputs
              data_out <= multiplier_int;

              -- Control Outputs
              out_valid <= '1';

              -- FSM Control
              multiplier_ctrl_fsm_int <= STARTER_STATE;
            else
              -- Data Internal
              multiplier_int <= std_logic_vector(signed(multiplier_int) - (signed(ZERO_SDATA) & signed(in1)));

              -- Control Internal
              index_loop <= std_logic_vector(signed(index_loop) - signed(ONE_SDATA));
            end if;
          elsif (in2(7) = '0') then
            if (signed(index_loop) = signed(in2)) then
              -- Data Outputs
              data_out <= multiplier_int;

              -- Control Outputs
              out_valid <= '1';

              -- FSM Control
              multiplier_ctrl_fsm_int <= STARTER_STATE;
            else
              -- Data Internal
              multiplier_int <= std_logic_vector(signed(multiplier_int) + (signed(ZERO_SDATA) & signed(in1)));

              -- Control Internal
              index_loop <= std_logic_vector(signed(index_loop) + signed(ONE_SDATA));
            end if;
          end if;

        when others =>
          -- FSM Control
          multiplier_ctrl_fsm_int <= STARTER_STATE;
      end case;
    end if;
  end process;

end architecture;
