#!/usr/bin/python
# -*- coding: UTF-8 -*-

def container(**boxes):
    def decorate(func):
        for k in boxes:
            setattr(func, k, boxes[k])
        return func
    return decorate

@container(box = [])
def lru(v):
    #lru.box
    if v in lru.box:
        lru.box.remove(v)
        lru.box.insert(0, v)
    else:
        maxlen = 3
        if(len(lru.box) > maxlen):
            lru.box.pop()
            lru.box.insert(0, v)
        else:
            lru.box.insert(0, v)
    return lru.box

print lru(1)
print lru(2)
print lru(3)
print lru(4)
print lru(5)
print lru(6)
print lru(8)
print lru(2)
print lru(1)
print lru(4)
print lru(9)