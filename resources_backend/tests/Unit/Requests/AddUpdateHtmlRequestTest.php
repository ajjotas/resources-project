<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\AddUpdateHtmlRequest;

class AddUpdateHtmlRequestTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidInputProvider
     */
    public function rules_invalidInputs_doesntPassValidation($inputs, $expectedError)
    {
        $rules = $this->getRules();        
        $validatorFactory = $this->getValidatorFactory();

        $validator = $validatorFactory->make($inputs, $rules);
        $validationPasses = $validator->passes();
        $validationErrors = $validator->failed();

        $this->assertFalse($validationPasses);
        $this->assertCount(1, $validationErrors);
        $this->assertArrayHasKey($expectedError['input'], $validationErrors);
        $this->assertCount(1, $validationErrors[$expectedError['input']]);
        $this->assertArrayHasKey($expectedError['rule'], $validationErrors[$expectedError['input']]);        
    }

    /**
     * @test
     * @dataProvider validInputProvider
     */
    public function rules_validInputs_passValidation($inputs)
    {
        $rules = $this->getRules();        
        $validatorFactory = $this->getValidatorFactory();

        $validator = $validatorFactory->make($inputs, $rules);
        $validationPasses = $validator->passes();

        $this->assertTrue($validationPasses);        
    }    



    protected function getRules() {
        $request = new AddUpdateHtmlRequest();
        return $request->rules();
    }

    protected function getValidatorFactory() {
        return $this->app['validator'];
    }

    public function invalidInputProvider()
    {
        return [
            'no_title' => [
                ['title' => '', 'description' => 'Test description', 'snippet' =>'Test snippet'],
                ['input' => 'title', 'rule' => 'Required']
            ],
            'no_description' => [
                ['title' => 'Test snippet', 'description' => '', 'snippet' =>'Test snippet'],
                ['input' => 'description', 'rule' => 'Required']                
            ],
            'no_snippet' => [
                ['title' => 'Test snippet', 'description' => 'Test description', 'snippet' => ''],
                ['input' => 'snippet', 'rule' => 'Required']                
            ]            
        ];
    }    

    public function validInputProvider()
    {
        return [
            'valid_inputs' => [
                ['title' => 'Test snippet', 'description' => 'Test description', 'snippet' =>'Test snippet']      
            ]
        ];
    }        


}
