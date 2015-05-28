require 'unicode_utils'

class StrTools
  def initialize(str)
    @str = str
  end

  def inspect_string
    return %w[] if @str.nil?
    UnicodeUtils.each_grapheme(@str).map do |g|
      { char: g, ord: g.ord, sid: UnicodeUtils.sid(g) }
    end
  end

  def nil?
    @str.nil?
  end
end