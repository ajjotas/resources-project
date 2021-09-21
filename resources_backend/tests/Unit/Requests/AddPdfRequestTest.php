<?php

namespace Tests\Unit\Requests;

use Illuminate\Http\UploadedFile;

use Tests\TestCase;
use App\Http\Requests\AddPdfRequest;

class AddPdfRequestTest extends TestCase
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
        $request = new AddPdfRequest();
        return $request->rules();
    }

    protected function getValidatorFactory() {
        return $this->app['validator'];
    }

    public function invalidInputProvider()
    {
        $htmlFile =  UploadedFile::fake()->create('document.html', '500');
        $bigFile =  UploadedFile::fake()->create('document.pdf', 5000);        

        return [
            'no_file' => [
                ['file' => null],
                ['input' => 'file', 'rule' => 'Required']
            ],
            'invalid_type_file' => [
                ['file' => 'Test file'],
                ['input' => 'file', 'rule' => 'Mimes']
            ],
            'invalid_mime_file' => [
                ['file' => $htmlFile],
                ['input' => 'file', 'rule' => 'Mimes']
            ],      
            'invalid_size_file' => [
                ['file' => $bigFile],
                ['input' => 'file', 'rule' => 'Max']
            ],               
        ];
    }    

    public function validInputProvider()
    {
        $pdfFile =  UploadedFile::fake()->create('document.pdf', 1000);    

        return [
            'valid_inputs' => [
                ['file' => $pdfFile],     
            ]
        ];
    }        


}
