#include <bits/stdc++.h>
using namespace std;

Node *deleteAtTailCircularDLL(Node *head){
    if(head==NULL){
        return NULL;
    }
    Node *move=head;
    if(move->next==head){
        delete head;
        return NULL;
    }

    while(move->next->next!=head){
        move=move->next;
    }
    Node *temp=move->next;
    delete temp;
    move->next=head;
    return head;
}

int main() {
    return 0;
}
