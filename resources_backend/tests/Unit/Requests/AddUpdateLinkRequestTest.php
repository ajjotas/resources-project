<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\AddUpdateLinkRequest;

class AddUpdateLinkRequestTest extends TestCase
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
        $request = new AddUpdateLinkRequest();
        return $request->rules();
    }

    protected function getValidatorFactory() {
        return $this->app['validator'];
    }

    public function invalidInputProvider()
    {
        return [
            'no_description' => [
                ['description' => '', 'link' => 'Test link', 'newTab' => true],
                ['input' => 'description', 'rule' => 'Required']
            ],
            'no_link' => [
                ['description' => 'Test description', 'link' => '', 'newTab' => true],
                ['input' => 'link', 'rule' => 'Required']              
            ],
            'no_newTab' => [
                ['description' => 'Test description', 'link' => 'Test link', 'newTab' => null],
                ['input' => 'newTab', 'rule' => 'Required']               
            ],
            'invalid_type_newTab' => [
                ['description' => 'Test description', 'link' => 'Test link', 'newTab' => 'Test new tab'],
                ['input' => 'newTab', 'rule' => 'Boolean']
            ],                        
        ];
    }    

    public function validInputProvider()
    {
        return [
            'valid_inputs' => [
                ['description' => 'Test description', 'link' => 'Test link', 'newTab' => true],      
            ]
        ];
    }        


}
