import { HttpClient } from "@angular/common/http";
import { EventEmitter } from "@angular/core";
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
    providedIn: 'root'
  })
export class ApiService {
    private apiUrl = 'http://localhost:8000/endpoint.php';

    constructor(private http:HttpClient){}

    get(params:any): Observable<any>{
        return this.http.get(this.apiUrl, {params});
    }

    getEntries(option:string, direction:string, word_limit?:number): Observable<any>{
        let responseEmitter: EventEmitter<any> = new EventEmitter();

        let params: any = {
            option: option,
            direction: direction
        };
        if(word_limit){
            params.word_limit = word_limit;
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