# legacy

lib.rootpid = TEXT
lib.rootpid.data = leveluid : 0

lib.currentLevel = TEXT
lib.currentLevel.data = level:1

lib.currentPid = TEXT
lib.currentPid.data = TSFE:id

# legacy end

lib.pidRoot = TEXT
lib.pidRoot.data = leveluid : 0

lib.pidCurrent = TEXT
lib.pidCurrent.data = TSFE:id

lib.level = TEXT
lib.level.data = level:1

lib.gpvar = COA
lib.gpvar {
  stdWrap.htmlSpecialChars = 1
  10 = TEXT
  10 {
    dataWrap = GP:{current}
    insertData = 1
    wrap3 = {|}
  }
}

lib.contentMain < styles.content.get
lib.contentMain.select.where = colPos = 0
