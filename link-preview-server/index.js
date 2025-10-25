const express = require('express');
const {getLinkPreview} = require("link-preview-js");
const linkPreview = require('link-preview-js').default;
const app = express();
const PORT = 3001;

app.get('/link-preview', async (req, res) => {
    const url = req.query.url;

    if (!url) {
        return res.status(400).json({error: 'URL is required'});
    }

    let preview

    // Fetch the link preview
    try {
        preview = await getLinkPreview(url, {
            headers: {
                'user-agent': 'googlebot',
            },
            followRedirects: `follow`, // May be a security risk
        });

        // Return the preview
        return res.json(preview);
    } catch (err) {
        console.error(preview);
        return res.status(500).json({error: 'Failed to fetch link preview'});
    }
});

app.listen(PORT, () => {
    console.log(`Link Preview Server is running on http://localhost:${PORT}`);
});
