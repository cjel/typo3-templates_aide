options {
    folderTree {
        uploadFieldsInLinkBrowser = 0
        hideCreateFolder = 1
    }
}

[applicationContext != 'Development']
options.clearCache.all = 0
options.clearCache.pages = 1
[end]
