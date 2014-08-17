require 'sinatra'

module NameUtils
  def hersir_name
    hersir_names = YAML.load_file("data/hersir.yml")

    first_name = hersir_names["first_names"].sample
    surname_first = hersir_names["surnames_first"].sample
    surname_second = hersir_names["surnames_second"].sample
    "#{ first_name } #{ surname_first }#{ surname_second }"
  end
end