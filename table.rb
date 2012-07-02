class Table
  attr_accessor :color, :seats, :material, :type, :legs

  def initialize(initial_hash=nil)
    initial_hash.each do |k,v|
      instance_variable_set("@#{k}", v)
    end if initial_hash
  end

  def pretty_print
    "The #{self.color} can seat #{seats}."
  end

  [:color, :seats, :material, :type, :legs].each do |attr|
    define_method("pretty_print_#{attr}") do
      "#{attr.to_s.capitalize}: #{instance_variable_set_get("@#{attr}")}"
    end
  end

  def map_pages
    maped_pages = pages
    pages.each_with_index do |page, i|
      maped_pages[i] = yield(page)
    end
    maped_pages
  end
end



# Unit tests

require 'minitest/autorun'

class TestTable < MiniTest::Unit::TestCase
  def setup
    @table = Table.new( { color: "black", seats: "4", :material => "wood", :type => "dining_table", :legs => "4" } )
  end

  def test_that_table_has_color
    assert_equal "black", @table.color
  end

  def test_that_table_has_seats
    assert_equal "4", @table.seats
  end

  def test_that_table_has_material
    assert_equal("wood", @table.material)
  end

  def test_that_table_has_type
    assert_equal "dining_table", @table.type
  end

  def test_that_table_has_legs
    assert_equal "4", @table.legs
  end

  def test_that_table_can_print_pretty
    assert_equal "The #{@table.color} can seat #{@table.seats}.", @table.pretty_print
  end
end
