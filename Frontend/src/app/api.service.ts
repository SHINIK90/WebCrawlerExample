import { HttpClient } from "@angular/common/http";
import { EventEmitter } from "@angular/core";
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
  })
export class ApiService {
    private apiUrl = 'http://localhost:8000/endpoint.php';                              //modify this if you start php server on a different port

    constructor(private http:HttpClient){}

    get(params:any): Observable<any>{                                                   //general get request
        return this.http.get(this.apiUrl, {params});
    }

    getEntries(option:string, direction:string, word_limit?:number): Observable<any>{   //get request to obtain entries given the parameters
        let responseEmitter: EventEmitter<any> = new EventEmitter();

        let params: any = {
            option: option,         //option: the type of filtering to request
            direction: direction    //direction: get entries in ascending or descending order 
        };
        if(word_limit){
            params.word_limit = word_limit;     //send a different word limit if needed
        }

        this.http.get(this.apiUrl,{params}).subscribe({
            next: (response: any) => {
                console.log("emit getEntries:", option,"-response", response);
                responseEmitter.emit(response);
                responseEmitter.complete();
            },
            error: (error: any) => {
                console.log("emit getEntries:", option,"-response error", error);
                responseEmitter.error(error);
            }
        });
        return responseEmitter;
    }
}