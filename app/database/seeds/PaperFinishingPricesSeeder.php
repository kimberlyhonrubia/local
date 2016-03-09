<?php

/**
 * PaperFinishingPricesSeeder Class
 *
 */


class PaperFinishingPricesSeeder extends Seeder {

    /**
     * The console command instance.
     *
     * @var \Illuminate\Console\Command
     */
    protected $command;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        DB::transaction(function()
        {
            $this->command->info('starting to seed paper_finishing_prices table.');

            $finishing_params = [
                [
                    'file_path' => storage_path().'/files/flyers_finishing_prices.xls',
                    'paper_product' => 'flyers',
                ],
                [
                    'file_path' => storage_path().'/files/bizcards_finishing_prices.xls',
                    'paper_product' => 'business cards',
                ]
            ];

            $color_params = [
                [
                    'file_path' => storage_path().'/files/150_color_prices.xls',
                    'paper_weight' => 150,
                ],
                [
                    'file_path' => storage_path().'/files/400_color_prices.xls',
                    'paper_weight' => 400,
                ]
            ];
            
            foreach ($finishing_params as $key => $data) {
                $this->finishingData($data);
            }

            foreach ($color_params as $key => $data) {
                $this->colorData($data);
            }

            $this->command->info('seed paper_finishing_prices table finished.');
        });
            
    }

    
    public function colorData($data)
    {
        $file_path     = $data['file_path'];
        $paper_weight  = $data['paper_weight'];
        $reader        = new SpreadsheetReader($file_path);
        $sheets        = $reader->Sheets();
        $pages         = [];

        foreach ($sheets as $key => $sheet) 
        {
            $reader->ChangeSheet(0);
            list($days, $color) = explode('|', $sheet);

            $ctr        = 1;
            $header     = [];

            foreach ($reader as $row)
            {   
                $row = array_filter($row);

                if (count($row) > 0)
                {
                    $k = 0;

                    if ($ctr == 2) // header index
                    {   
                        for ($i = 1; $i < count($row) ; $i++ ) 
                        {
                            $header[$k] = $row[$i];
                            $k++;
                        }
                    }
                    elseif ($ctr > 2) // contents
                    {
                        for ($i = 0; $i < count($header) ; $i++ ) 
                        {
                            if ($row[0])
                            {
                                $price = preg_replace("/[^0-9,.]/", "", $row[$i+1]);

                                $data = [
                                    'paper_size_code' => $header[$i], // change this to size_code id
                                    'color_code'      => $color,      // change to color_code id
                                    'paper_weight'    => $paper_weight,
                                    'day'             => $days,
                                    'quantity'        => $row[0] == '' ? 'x' : $row[0],
                                    'price'           => $price  == '' ? 'x' : $price,
                                ];

                                if (!empty($data['paper_size_code']))
                                    DB::table('paper_color_prices')->insert($data);
                            } // END if ($row[0])
                        } // END for ($i = 0; $i < count($header) ; $i++ ) 
                    } // END if ($ctr == 2) 
                } // END if (count($row) > 0)
                $ctr++;
            } // END foreach ($reader as $row)
        } // END foreach ($sheets as $key => $sheet)

    }

    public function finishingData($data)
    {
        $file_path     = $data['file_path'];
        $paper_product = $data['paper_product'];
        $reader    = new SpreadsheetReader($file_path);
        $sheets    = $reader->Sheets();
        $pages     = [];

        foreach ($sheets as $key => $sheet) 
        {
            $reader->ChangeSheet($key);
            $page = $this->parseDataOnPage($reader, $sheet, $paper_product);
        }

    }


    public function parseDataOnPage($reader, $finishing, $paper_product)
    {
        $ctr        = 1;
        $header     = [];
        $temp_array = [];
        $k = 0;
        $l = 0; 

        foreach ($reader as $row)
        {   
            if ($ctr == 3)
            {
                for($i=0; $i<count($row); $i++)
                {
                    $remainder = $i % 2;
                    $option_string = '';

                    if ($remainder)
                    {
                        $options = explode("|", $row[$i]);

                        foreach ($options as $key => $value) 
                        {
                            $option = explode(":=", $value);
                            $option_string .= @$option[1].'|';
                        }

                        if($option_string != '' && !in_array($option_string, $temp_array))
                        {
                            $header[$k]['finishing_code'] = $option_string;
                            array_push($temp_array, $option_string);
                            $k++;
                        }
                    }
                }
            }
            else if ($ctr >= 4)
            {
                $l = 1;

                for ($i = 0; $i < count($header) ; $i++ ) 
                { 
                    if ($ctr == 4 || $ctr == 5)
                    {   
                        $name = $ctr == 4 ? 'minimum_price' : 'minimum_folded_size';
                        $header[$i][$name] = $row[$l];
                    }
                    else if ($ctr > 5)
                    {
                        $data = [
                            'paper_size_code'     => $row[0], // change this size_code id
                            'finishing_code'      => $finishing,
                            'paper_product'       => $paper_product,
                            'minimun_price'       => $header[$i]['minimum_price'],
                            'minimum_folded_size' => $header[$i]['minimum_folded_size'],
                            'price_per_page'      => $row[$l],
                            'turn_around_time'    => $row[$l+1],
                        ];

                        $options = explode('|', $header[$i]['finishing_code']);
                        $m = 1;
                        foreach ($options as $key => $value) 
                        {
                            if ($value != '')
                            {
                                $data['option_'.$m.'_code'] = $value;
                                $m++;
                            }
                        }

                        if ($header[$i]['minimum_price'] > 0)
                            DB::table('paper_finishing_prices')->insert($data);
                    } // END if ($ctr == 4 || $ctr == 5)
                    
                    $l += 2;
                } // END for ($i = 0; $i < count($header) ; $i++ ) 
            } // END if ($ctr == 3)

            $ctr++;
        } // END foreach ($reader as $row)

    }


}